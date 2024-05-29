<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view(
            "admin.articles.index",
            compact("articles")
        );
    }

    public function create()
    {
        $categories = Category::all();
        return view("admin.articles.create", compact('categories'));
    }
    public function store(Request $request) 
    {
        $request->validate([
            'name' =>'required',
            'description' =>'required',
            'price' =>'required',
            'count' =>'required',
            'category' =>'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $article = Article::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'count' => $request->count,
            'discount' => $request->discount ?? 0,
            'installation_fees'=> $request->installation_fees ?? 0,
            'delivery_fees'=> $request->delivery_fees ?? 0,
            'days_to_delivery'=> $request->days_to_delivery ?? 0,
            'category_id' => $request->category
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $article
                        ->addMedia($image)
                        ->toMediaCollection('ArticleImages');
                }
            }
        }
       
        return redirect()
                ->route("articles")
                ->with("message", "article ajouté avec succès !");
    }
    public function edit($id)
    {
        $article = Article::find($id);
        $categories = Category::all();
        return view("admin.articles.edit", compact('article', 'categories'));
    }

    public function update(Request $request, $id) 
    {
        $article = Article::find($id);
        $request->validate([
            'name' =>'required',
            'description' =>'required',
            'price' =>'required',
            'count' =>'required',
            'category' =>'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $article = Article::find($id);
        $article->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'count' => $request->count,
            'discount' => $request->discount ?? 0,
            'installation_fees'=> $request->installation_fees ?? 0,
            'delivery_fees'=> $request->delivery_fees ?? 0,
            'days_to_delivery'=> $request->days_to_delivery ?? 0,
            'category_id' => $request->category
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $article
                        ->addMedia($image)
                        ->toMediaCollection('ArticleImages');
                }
            }
        }
       
        return redirect()
                ->route("articles")
                ->with("message", "article mis à jour avec succès !");
    }
    public function delete($id)
    {
        $article = Article::find($id);
        $article->delete();
        return redirect()
                ->route("articles")
                ->with("message", "article supprimé avec succès !");
    }
    public function deleteMedia($id)
    {
        $image = Media::find($id);
        $model = $image->model_id;
        $image->delete();
        return redirect()
                ->route("articles.edit", ['id' => $model])
                ->with("message", "image supprimée avec succès !");
    }

    public function details($id)
    {
        $article = Article::find($id);
        $categories = Category::all();
        $article->vues = $article->vues + 1;
        $article->save();
        $others = Article::where('id', '<>', $article->id)->where('category_id', $article->category->id)->where('count', '>', 0)->orderByDesc('vues')->limit(3)->get();
        return view('admin.articles.details', compact('article', 'categories', 'others'));
    }

    public function search(Request $request)
    {
        $categories = Category::all();        
        $articles = Article::where(function($query) use ($request) {
            $query->where("name", "like", "%".$request->search."%")
                  ->orWhere("description", "like", "%".$request->search."%");
        })
        ->where('count', '>', 0)
        ->orderByDesc('vues')
        ->paginate(20);
        return view('welcome', compact('categories', 'articles'));
    }
}
