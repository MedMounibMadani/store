<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Message;
use App\Models\Offer;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->get();
        return view(
            "admin.messages.index",
            compact('messages')
        );
    }

    public function detail($id)
    {
        $message = Message::find($id);
        if ( ! $message->seen )
        {
            $message->seen = 1;
            $message->save();
        }
        return view(
            "admin.messages.detail",
            compact('message')
        );
    }

    public function devisRequest($id)
    {
        $categories = Category::all();
        $offer = Offer::find($id);
        return view(
            "admin.offers.devis",
            compact('categories', 'offer')
        );
    }
    public function devisStore(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'offer' => 'required'
        ]); 
        $message = Message::create([
            'email' => $request->email,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'offer_id' => $request->offer,
            'entreprise' => $request->entreprise ?? '',
            'message' => $request->message ?? '',
        ]);
        return redirect()->route('welcome')->with('messageSent', 'Votre message a été envoyé avec succès !');
    }
}
