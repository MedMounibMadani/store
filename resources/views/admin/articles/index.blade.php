@extends('admin.dashboard')
@section('style')
<style>
   svg:hover {
    scale: 1.2;
   }
</style>
@endsection
@section('content')
<div class="container mt-4">
    <div class="my-2">
        <a href="{{ route('articles.create') }}">
            <button class="btn btn-success">
                Ajouter un Article
            </button>
        </a>
    </div>
    <table id="articleList" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Date d'ajout</th>
                <th>Prix HT</th>
                <th>Solde(%)</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($articles as $article)
                <tr>
                    <td> {{ $article->name }} </td>
                    <td> {{ $article->category->name ?? '' }} </td>
                    <td> {{ \Carbon\Carbon::parse($article->created_at)->format('d/m/Y') }} </td>
                    <td> 
                        {{ number_format( round($article->price) , 2, ',', ' ' ). '€' }}
                    </td>
                    <td> {{ $article->discount }} </td>
                    <td> 
                        <span class="d-flex align-items-center justify-content-start">
                        @if ( $article->count > 5 ) 
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="color: green;" class="mr-2 bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg> en stock. 
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="color: red;" class="mr-2 bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                        </svg>
                         {{ $article->count }} derniers articles.
                        @endif
                        </span>
                    </td>
                    <td>
                        <span class="d-flex align-items-center justify-content-start">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#ArtModal{{ $article->id }}">
                            Suppr
                        </button>
                        <div class="modal fade" id="ArtModal{{ $article->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-body">
                                    Etes-vous sur de vouloir supprimer cet article : <b> {{ $article->name }} </b> 
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <a class="btn btn-danger" href="{{ route('articles.delete', ['id' => $article->id] ) }}">
                                        Oui supprimer
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>
                        <a class="ml-2 btn btn-warning" href="{{ route('articles.edit', ['id' => $article->id] ) }}">
                           Màj
                        </a>
                        </span>
                    </td>
                </tr>     
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('javascript')
<script>
    new DataTable('#articleList', {
    language: {
        info: 'Page _PAGE_ de _PAGES_',
        infoEmpty: 'Pas d\'articles disponibles',
        infoFiltered: '(filtré de _MAX_ articles)',
        lengthMenu: 'Afficher _MENU_ articles par page',
        zeroRecords: 'Aucun article trouvé'
    }
});
</script>
@endsection