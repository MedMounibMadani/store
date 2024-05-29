@extends('admin.dashboard')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form enctype="multipart/form-data" action="{{ route('articles.update', ['id' => $article->id] ) }}" method="post">
        @csrf
        @method('PUT')
        <h2>
            Modifier un article
        </h2>
        <div class="my-4">
            <label class="form-label">Nom</label>
            <input name="name" type="text" value="{{ $article->name }}" class="form-control">
        </div>
        <div class="row">
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">Catégorie</label>
                    <select class="form-select" aria-label="Default select example" name="category"> 
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ isset($article->category->id) && $article->category->id == $category->id ? 'selected' : '' }}> {{ $category->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-3">
            <div class="mb-3">
                <label class="form-label">Pourcentage de solde (%)</label>
                <input name="discount" type="number" min="0" max="99" value="{{ $article->discount }}" class="form-control">
            </div>
            </div>
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">Prix (€) HT</label>
                    <input name="price" type="number" value="{{ $article->price }}" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">Nombre d'articles disponible</label>
                    <input name="count" type="number" min="1" value="{{ $article->count }}" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">Frais de livraison (€)</label>
                    <input name="delivery_fees" type="number" value="{{ $article->delivery_fees }}" class="form-control">
                </div>
            </div>
            <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">Nombre de jours pour livrer</label>
                    <input name="days_to_delivery" type="number" value="{{ $article->days_to_delivery }}" class="form-control" required>
                </div>
            </div>
            <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">Frais d'installation (€)</label>
                    <input name="installation_fees" type="number" value="{{ $article->installation_fees }}" class="form-control">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label> <br>
            <textarea name="description" cols="50" rows="7" class="form-control"> {{ $article->description }} </textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Photos</label> <br>
            @foreach ($article->getMedia('ArticleImages') as $item)
            <a role="button" data-bs-toggle="modal" data-bs-target="#ImG{{ $item->id }}">
                <img src="{{ $item->getUrl('extra_thumb') }}" class="mx-2" alt="image"> 
            </a>
            <div class="modal fade" id="ImG{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <img src="{{ $item->getUrl('thumb') }}" alt="image"> 
                    <a class="btn btn-danger" href="{{ route('articles.media.delete', ['id' => $item->id ] ) }}">
                        Supprimer
                    </a>
                </div>
            </div>
            </div>
            @endforeach
            <br>
            <input type="file" class="form-control" name="images[]" multiple />
            @error('images.*')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>       
        <button type="submit" class="btn btn-success">Sauvegarder</button>
    </form>
</div>
@endsection