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
        <a href="{{ route('categories.create') }}">
            <button class="btn btn-success">
                Ajouter une Catégorie
            </button>
        </a>
    </div>
    <table id="catList" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Date d'ajout</th>
                <th>Nombre d'articles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td> {{ $category->name }} </td>
                    <td> {{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y') }} </td>
                    <td> {{ $category->articles()->count() }} </td>                    
                    <td>
                        <span class="d-flex align-items-center justify-content-start">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#CatModal{{ $category->id }}">
                            Suppr
                        </button>
                        <div class="modal fade" id="CatModal{{ $category->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-body">
                                    Etes-vous sur de vouloir supprimer cette catégorie : <b> {{ $category->name }} </b> 
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <a class="btn btn-danger" href="{{ route('categories.delete', ['id' => $category->id] ) }}">
                                        Oui supprimer
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>
                        <a class="ml-2 btn btn-warning" href="{{ route('categories.edit', ['id' => $category->id] ) }}">
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
    new DataTable('#catList', {
    language: {
        info: 'Page _PAGE_ de _PAGES_',
        infoEmpty: 'Pas de catégories disponibles',
        infoFiltered: '(filtré de _MAX_ catégories)',
        lengthMenu: 'Afficher _MENU_ catégories par page',
        zeroRecords: 'Aucune catégorie trouvée'
    }
});
</script>
@endsection