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
    <form action="{{ isset($category) ? route('categories.update', ['id' => $category->id] ) : route('categories.store') }}" method="POST">
        @csrf
        @if( isset($category) )
            @method('PUT')
        @endif
        <h2>
            Ajouter une catégorie
        </h2>
        <div class="my-4">
            <label class="form-label">Nom</label>
            <input name="name" type="text" value="{{ $category->name ?? '' }}" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-success">{{ isset($category) ? 'Sauvegarder' : 'Ajouter' }}</button>
    </form>
</div>
@endsection