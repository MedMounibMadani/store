@extends('admin.dashboard')

@section('content')
<div class="container">
    <h5>Liste des articles les plus consultés</h5>
    <ol class="list-group list-group-numbered">
        @foreach( $topSeen as $seen )
            <li class="list-group-item"> <a href="{{ route('articles.edit', $seen->id ) }}"> <b> {{ $seen->name }} </b> </a> : {{ $seen->vues }} fois </li>
        @endforeach
    </ol>
</div>
<div class="container mt-4">
    <h5>Liste des articles les plus vendus </h5>
    <ol class="list-group list-group-numbered">
        @foreach( $topSold as $sold )
            <li class="list-group-item"> <a href="{{ route('articles.edit', $sold->id ) }}"> <b> {{ $sold->name }} </b> </a> : {{ $sold->commands_counter_sum }} unités </li>
        @endforeach
    </ol>
</div>
<div class="container">
    <div class="row mt-4">
        <div class="card col-12">
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
        <div class="card mt-4 col-4" style="border: none;">
            <h5 class="card-header">Articles</h5>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Vous avez {{ $arts }} article(s) </h5>
                    </div>
                    <a href="{{ route('articles') }}" class="btn btn-warning">Consulter</a>
                </div>
            </div>
        </div>
        <div class="card mt-4 col-4" style="border: none;">
            <h5 class="card-header">Catégories</h5>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Vous avez {{ $cats }} catégorie(s) </h5>
                    </div>
                    <a href="{{ route('categories') }}" class="btn btn-warning">Consulter</a>
                </div>
            </div>
        </div>
        <div class="card mt-4 col-4" style="border: none;">
            <h5 class="card-header">Offres</h5>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Vous avez {{ $offers }} offre(s) </h5>
                    </div>
                    <a href="{{ route('offers') }}" class="btn btn-warning">Consulter</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection