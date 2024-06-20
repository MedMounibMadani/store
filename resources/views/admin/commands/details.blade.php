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
    @if( $command->status === "NEW" )
    <div class="d-flex justify-content-end">
        <a href="{{ route('commands.done', $command->id ) }}" class="btn btn-danger">  Classer la commande comme traitée </a>
    </div>
    @endif
    <p> Commande N° {{ $command->id . ' ( '. \Carbon\Carbon::parse($command->created_at)->format('d/m/Y h:i') . ' )' }} </p>
    @if( $command->status === "NEW" ) 
        @if( $command->delivery )
            <button class="btn btn-success">
                à livrer {{ $command->delivery_date ? 'avant le '. \Carbon\Carbon::parse($command->delivery_date)->format('d/m/Y') : '' }}
            </button>
        @endif
    @endif
    <div class="row mt-4">
        <div class="col-4">
            <div class="card text-dark bg-light mb-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="{{ isset($command->user->id) ? 'background-color: goldenrod !important;' : '' }}" >
                    <b>Client</b> 
                    @if( isset($command->user->id) )
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title pb-2"> {{ $command->first_name . ' ' . strtoupper($command->last_name) }} </h5>
                    <p class="card-text"> <b> Email : </b> {{ $command->email }} </p>
                    <p class="card-text"> <b> Tél : </b> {{ $command->phone }} </p>
                    <p class="card-text"> <b> Adresse : </b> {{ $command->address .', '. $command->zip_code .' '. $command->city .' '. $command->country }} </p>
                    <p class="card-text"> <b> Message du client : </b> {{ $command->message }} </p>
                </div>
            </div>
        </div>
        <div class="col-8">
            @foreach( $command->articles as $article )
            <div class="card text-dark bg-light mb-3">
                <div class="card-header"> <b> {{ $article->pivot->count }} x </b> <span style="color: mediumblue;"> {{ $article->name }} </span> </div>
                <div class="card-body">
                    <p class="card-text d-flex justify-content-end"> <a class="btn btn-dark" href="{{ route('articles.edit', $article->id) }}"> Consulter la fiche de cet article </a> </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
   
</script>
@endsection