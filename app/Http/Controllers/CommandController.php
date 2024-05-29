<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Command;
use App\Jobs\SendEmail;
use App\Mail\CommandRecieved;
use Illuminate\Http\Request;
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
            'installFee' => 'required',
            'install' => 'required'
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
        return redirect()->route('get.step.two');
    }
    
    public function stepThree( Request $request ) 
    {
        // $user = auth()->user();
        // if ( ! $user ) {
        //     $request->validate(
        //         [
        //             'first_name' =>'required',
        //             'last_name' =>'required',
        //             'email' =>'required',
        //             'phone' =>'required',
        //             'address' =>'required',
        //             'city' =>'required',
        //             'zip_code' =>'required',
        //             'country' =>'required',
        //         ]
        //     );
        // }
        // $command = Command::create([
        //     'user_id' => $user? $user->id : null,
        //     'first_name' => ucfirst($request->first_name),
        //     'last_name' => strtoupper($request->last_name),
        //     'email' => $request->email,
        //     'phone' => $request->phone,
        //     'address' => $request->address,
        //     'city' => $request->city,
        //     'zip_code' => $request->zip_code,
        //     'country' => strtoupper($request->country),
        //     'delivery' =>  Session::get('delivery'),
        //     'installation' =>  Session::get('installation'),
        //     'checkout' => round( Session::get('totalTTC') ), 
        //     'status' => 'NEW'
        // ]);
        // foreach ( Session::get('quantity') as $articleId => $count) {
        //     $command->articles()->attach($articleId, ['count' => $count]);
        // }
        // SendEmail::dispatch(new CommandRecieved($command), $command->email);   

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
        dd($paymentIntent->client_secret);
        Session::forget('articles');
        Session::forget('quantity');
        Session::forget('totalTTC');
        Session::forget('totalHT');
        Session::forget('delivery');
        Session::forget('installation');

        return redirect()->route('welcome')->with('commandSuccess', 'Votre commande à été bien prise, Merci et à bientôt.');
    }
}
