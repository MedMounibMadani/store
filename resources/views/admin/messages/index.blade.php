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
    <table id="MessList" class="display" style="width:100%">
        <thead>
            <tr>
                <th>De</th>
                <th>Date</th>
                <th>Email</th>
                <th>Tél</th>
                <th>Offre</th>
                <th>Voir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($messages as $message)
                <tr style="background-color: {{ ! $message->seen ? 'lemonchiffon' : '' }}; font-weight: {{ ! $message->seen ? 'bold' : '' }};" >
                    <td> {{ $message->full_name }} </td>
                    <td> {{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y h:i') }} </td>
                    <td> {{ $message->email }} </td>
                    <td> {{ $message->phone }} </td>
                    <td> {{ $message->offer->title ?? '' }} </td>                    
                    <td>
                        <a href="{{ route('messages.detail', $message->id) }}" class="btn btn-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" style="color: white;" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>
                        </a>
                    </td>
                </tr>     
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('javascript')
<script>
    new DataTable('#MessList', {
    language: {
        info: 'Page _PAGE_ de _PAGES_',
        infoEmpty: 'Pas de messages',
        infoFiltered: '(filtré de _MAX_ messages)',
        lengthMenu: 'Afficher _MENU_ messages par page',
        zeroRecords: 'Aucun message trouvé'
    }
});
</script>
@endsection