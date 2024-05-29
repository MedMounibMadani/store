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
        <a href="{{ route('offers.create') }}">
            <button class="btn btn-success">
                Ajouter une offre
            </button>
        </a>
    </div>
    <table id="offersList" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Date d'ajout</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($offres as $offer)
                <tr>
                    <td> {{ $offer->title }} </td>
                    <td> {{ $offer->description }} </td>
                    <td> {{ \Carbon\Carbon::parse($offer->created_at)->format('d/m/Y') }} </td>
                    <td> 
                        @if ( $offer->status ) 
                        <a href="{{ route('offers.visibility', $offer->id) }}">
                            <button class="btn btn-danger">
                                Retirer
                            </button>
                        </a>
                        @else
                        <a href="{{ route('offers.visibility', $offer->id) }}">
                            <button class="btn btn-success">
                                Publier
                            </button>
                        </a>
                        @endif
                    </td>
                    <td>
                        <span class="d-flex align-items-center justify-content-start">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#OfferModal{{ $offer->id }}">
                            Suppr
                        </button>
                        <div class="modal fade" id="OfferModal{{ $offer->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-body">
                                    Etes-vous sur de vouloir supprimer cette offre : <b> {{ $offer->title }} </b> 
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <a class="btn btn-danger" href="{{ route('offers.delete', ['id' => $offer->id] ) }}">
                                        Oui supprimer
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>
                        <a class="ml-2 btn btn-warning" href="{{ route('offers.edit', ['id' => $offer->id] ) }}">
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
    new DataTable('#offersList', {
    language: {
        info: 'Page _PAGE_ de _PAGES_',
        infoEmpty: 'Pas des offres disponibles',
        infoFiltered: '(filtré de _MAX_ offres)',
        lengthMenu: 'Afficher _MENU_ offres par page',
        zeroRecords: 'Aucune offre trouvée'
    }
});
</script>
@endsection