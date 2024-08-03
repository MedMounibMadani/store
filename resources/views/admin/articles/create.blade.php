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
    <form enctype="multipart/form-data" action="{{ route('articles.store') }}" method="POST">
        @csrf
        <h2>
            Ajouter un article
        </h2>
        <div class="my-4">
            <label class="form-label">Nom</label>
            <input name="name" type="text" class="form-control">
        </div>
        <div class="row">
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">Catégorie</label>
                    <select class="form-select" aria-label="Default select example" name="category"> 
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"> {{ $category->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">Pourcentage de solde (%)</label>
                    <input name="discount" type="number" min="0" max="99" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">Prix (€) HT </label>
                    <input name="price" type="number" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">Nombre d'articles disponible</label>
                    <input name="count" type="number" min="1" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">Frais de livraison (€)</label>
                    <input name="delivery_fees" type="number" class="form-control">
                </div>
            </div>
            <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">Nombre de jours pour livrer</label>
                    <input name="days_to_delivery" type="number" class="form-control" required>
                </div>
            </div>
            {{-- <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">Frais d'installation (€)</label>
                    <input name="installation_fees" type="number" class="form-control">
                </div>
            </div> --}}
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label> <br>
            <textarea name="description" cols="50" rows="7" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Photos</label> <br>
            <input type="file" class="form-control" name="images[]" multiple />
            @error('images.*')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>       
        <button type="submit" class="btn btn-success">Ajouter</button>
    </form>
</div>
@endsection