<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view(
            "admin.categories.index",
            compact("categories")
        );
    }

    public function create()
    {
        return view("admin.categories.create");
    }
    public function store(Request $request) 
    {
        $request->validate([
            'name' =>'required',
        ]);
        $category = Category::create([
            'name' => $request->name,
        ]);
       
        return redirect()
                ->route("categories")
                ->with("message", "Categorie ajoutée avec succès !");
    }
    public function edit($id)
    {
        $category = Category::find($id);
        return view("admin.categories.create", compact('category'));
    }

    public function update(Request $request, $id) 
    {
        $categorie = Category::find($id);
        $request->validate([
            'name' =>'required',
        ]);
        $categorie->update([
            'name' => $request->name,
        ]);
       
        return redirect()
                ->route("categories")
                ->with("message", "Categorie mise à jour avec succès !");
    }
    public function delete($id)
    {
        $categorie = Category::find($id);
        $categorie->delete();
        return redirect()
                ->route("categories")
                ->with("message", "Categorie supprimée avec succès !");
    }
}
