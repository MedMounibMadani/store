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
                <th>Date de réception</th>
                <th>Date de traitement</th>
                <th>Livraison insluse</th>
                <th>Installation incluse</th>
                <th>Détails</th>
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
                    <td> {{ \Carbon\Carbon::parse($command->updated_at)->format('d/m/Y h:i') }} </td>
                    <td> {{ $command->delivery == 1 ? 'Oui' : 'Non' }} </td>
                    <td> {{ $command->installation == 1 ? 'Oui' : 'Non' }} </td>
                    <td>
                        <a href="{{ route('commands.detail', $command->id ) }}" class="btn btn-success"> Consulter </a>
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