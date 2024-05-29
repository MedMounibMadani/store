@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <div class="card">
        <h5 class="card-header">Commandes</h5>
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title">Vous avez {{ $cmds }} commande(s) </h5>
                    <p class="card-text">Consultez la liste des commandes et suivez leurs statuts, ainsi que l'historique des commandes.</p>
                </div>
                <a href="{{ route('commands') }}" class="btn btn-primary">Consulter les commandes</a>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <h5 class="card-header">Articles</h5>
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title">Vous avez {{ $arts }} article(s) </h5>
                    <p class="card-text">Ajoutez, modifiez et consultez la liste de vos articles.</p>
                </div>
                <a href="{{ route('articles') }}" class="btn btn-primary">Consulter les articles</a>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <h5 class="card-header">Catégories</h5>
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title">Vous avez {{ $cats }} catégorie(s) </h5>
                    <p class="card-text">Ajoutez, modifiez et consultez la liste des catégories d'article.</p>
                </div>
                <a href="{{ route('categories') }}" class="btn btn-primary">Consulter les catégories</a>
            </div>
        </div>
    </div>
</div>
@endsection