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
    <table id="commandsList" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Client</th>
                <th>Somme (€)</th>
                <th>Date</th>
                <th>Livraison insluse</th>
                <th>Installation incluse</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commands as $command)
                <tr>
                    <td> {{ $command->id }} </td>
                    <td> {{ $command->first_name . ' ' . $command->last_name  }} </td>
                    <td> 
                        {{ number_format( round($command->checkout) , 2, ',', ' ' ). '€' }}
                    </td>
                    <td> {{ \Carbon\Carbon::parse($command->created_at)->format('d/m/Y h:i') }} </td>
                    
                    <td> {{ $command->delivery == 1 ? 'Oui' : 'Non' }} </td>
                    <td> {{ $command->installation == 1 ? 'Oui' : 'Non' }} </td>
                    <td>
                        <a href="{{ route('commands.detail', $command->id ) }}" class="btn btn-success"> Consulter </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cmd{{ $command->id }}">
                            Traitée
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="cmd{{ $command->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Etes vous sur de vouloir classer la commande de Mr/Mme {{ $command->first_name . ' ' . $command->last_name  }} comme traitée ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <a href="{{ route('commands.done', $command->id ) }}" class="btn btn-danger"> Oui, je confirme </a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </td>
                </tr>     
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('javascript')
<script>
    new DataTable('#commandsList', {
    language: {
        info: 'Page _PAGE_ de _PAGES_',
        infoEmpty: 'Aucune commande',
        infoFiltered: '(filtré de _MAX_ commandes)',
        lengthMenu: 'Afficher _MENU_ commandes par page',
        zeroRecords: 'Aucunne commande trouvée'
    }
});
</script>
@endsection