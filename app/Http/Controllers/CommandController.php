<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Command;
use App\Jobs\SendEmail;
use App\Mail\CommandRecieved;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CommandController extends Controller
{
    public function index()
    {
        $commands = Command::where('status', 'NEW')->orderBy('created_at', 'desc')->get();
        return view(
            "admin.commands.index",
            compact("commands")
        );
    }

    public function history()
    {
        $commands = Command::where('status', 'DONE')->get();
        return view(
            "admin.commands.history",
            compact("commands")
        );
    }

    public function details($id)
    {
        $command = Command::find($id);
        return view(
            "admin.commands.details",
            compact("command")
        );
    }

    public function setToDone($id)
    {
        $command = Command::find($id);
        $command->status = "DONE";
        $command->save();
        return redirect()->back()->with("message", "Commande N°". $command->id . " est bien enregistrée comme traitée.");
    }
    
    public function getStepOne()
    {
        $articles = Session::get('articles');
        $categories = Category::all();
        return view(
            "payment.step-one",
            compact("articles", "categories")
        );
    }

    public function stepOne( Request $request ) 
    {
        $request->validate([
            'checkout' => 'array|min:1'
        ]);
        $articles = [];
        foreach( $request->checkout as $item )
        {
            $article = Article::findOrFail($item["id"]);
            if ($article && ! in_array($article, $articles)) {
                array_push($articles, $article);
            }
        }
        Session::put('articles', $articles);
        return response()->json(['status' => 'success']);
    }
    public function getStepTwo()
    {
        $totalHT = Session::get('totalHT');
        $totalTTC = Session::get('totalTTC');
        $categories = Category::all();
        return view(
            "payment.step-two",
            compact("totalTTC", "totalHT", "categories")
        );
    }

    public function stepTwo( Request $request ) 
    {
        $request->validate([
            'articles' => 'required|array',
            'delivery' => 'required',
            'deliveryFee' => 'required',
            // 'installFee' => 'required',
            // 'install' => 'required'
        ]);
        $totalTTC = 0;
        $totalHT = 0;
        $fees = ( $request->delivery == 1 ? $request->deliveryFee : 0 ) + ( $request->install == 1 ? $request->installFee : 0 );
        foreach ( Session::get('articles') as $article ) {
            $totalTTC += $article->price * $request->articles[$article->id] * (1 - $article->discount / 100) * 1.2;
            $totalHT += $article->price * $request->articles[$article->id] * (1 - $article->discount / 100);
        }
        Session::put('quantity', $request->articles);
        Session::put('delivery', $request->delivery);
        Session::put('installation', $request->install);
        Session::put('totalTTC', $totalTTC + $fees);
        Session::put('totalHT', $totalHT + $fees);
        Session::put('clientMessage', $request->message ?? '');
        return redirect()->route('get.step.two');
    }
    
    public function stepThree( Request $request ) 
    {
          

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Get the payment amount and email address from the form.
        $amount = round( Session::get('totalTTC') ) * 100;
        $email = $request->email;

        // Create a new Stripe customer.
        $customer = \Stripe\Customer::create([
            'email' => $email,
            'source' => $request->input('stripeToken'),
        ]);
        
        // Create a new Stripe charge.
        $paymentIntent = \Stripe\PaymentIntent::create([
            'customer' => $customer->id,
            'amount' => $amount,
            'currency' => 'eur',
            'payment_method_types' => ['card'],
        ]);
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function success( Request $request )
    {
        try {
            $user = auth()->user();
            $command = Command::create([
                'user_id' => $user? $user->id : null,
                'first_name' => ucfirst($request->first_name),
                'last_name' => strtoupper($request->last_name),
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'country' => strtoupper($request->country),
                'delivery' =>  Session::get('delivery'),
                'installation' =>  Session::get('installation') ?? false,
                'checkout' => round( Session::get('totalTTC') ), 
                'message' =>  Session::get('clientMessage'),
                'status' => 'NEW'
            ]);
            $deliveryDates = [];
            foreach ( Session::get('quantity') as $articleId => $count) {
                $articleItem = Article::find($articleId);
                $articleDelivery = $articleItem->days_to_delivery;
                if ( ! in_array($articleDelivery, $deliveryDates) ) {
                    array_push($deliveryDates, $articleDelivery);
                }
                $articleItem->count = $articleItem->count - $count; 
                $articleItem->save();
                $command->articles()->attach($articleId, ['count' => $count]);
            }
            if (max($deliveryDates) > 0) {
                $command->delivery_date = \Carbon\Carbon::now()->addDays( max($deliveryDates) );
                $command->save();
            }
            SendEmail::dispatch(new CommandRecieved($command), $command->email);
            Session::forget(['articles', 'quantity', 'totalTTC', 'totalHT', 'delivery', 'installation']);
            return response()->json([
                'message' => 'done',
                'url' => config('app.url')
            ]);
        } catch (Exception $e) { 
            Log::error('Command creation error: ' . $e->getMessage());
            Log::error($request->all());
            return  response()->json([
                'message' => 'error',
                'url' => config('app.url')
            ]);
        }
        
    }
    public function postSuccess() 
    {
        return redirect()->route('welcome')->with('commandSuccess', 'Votre commande a été bien prise, un e-mail de confirmation vous a été envoyé, dans le cas échéant veuillez nous contacter. Merci et à bientôt !');
    }
    
}
