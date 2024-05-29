<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;

class OfferController extends Controller
{
    public function index()
    {
        $offres = Offer::all();
        return view(
            "admin.offers.index",
            compact("offres")
        );
    }

    public function create()
    {
        return view("admin.offers.create");
    }
    public function store(Request $request) 
    {
        $request->validate([
            'title' =>'required',
            'description' =>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $offer = Offer::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->hasFile('image')) {
            $offer
                ->addMedia($request->image)
                ->toMediaCollection('OfferImages');
        }
       
        return redirect()
                ->route("offers")
                ->with("message", "offre ajoutée avec succès !");
    }
    public function edit($id)
    {
        $offer = Offer::find($id);
        return view("admin.offers.create", compact('offer'));
    }

    public function update(Request $request, $id) 
    {
        $offer = Offer::find($id);
        $request->validate([
            'title' =>'required',
            'description' =>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $offer->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->hasFile('image')) {
            $offer
                ->addMedia($request->image)
                ->toMediaCollection('OfferImages');
        }
       
        return redirect()
                ->route("offers")
                ->with("message", "offre mise à jour avec succès !");
    }
    public function delete($id)
    {
        $offer = Offer::find($id);
        $offer->delete();
        return redirect()
                ->route("offers")
                ->with("message", "offre supprimée avec succès !");
    }

    public function deleteMedia($id)
    {
        $offer = Offer::find($id);
        $image = $offer->getMedia('*')[0];
        $image->delete();
        return redirect()
                ->route("offers.edit", $id)
                ->with("message", "image supprimée avec succès !");
    }

    public function visibility($id) 
    {
        $offer = Offer::find($id);
        $offer->status = ! $offer->status;
        $offer->save();
        return redirect()
                ->route("offers")
                ->with("message", "offre modifiée avec succès !");
    }
}
