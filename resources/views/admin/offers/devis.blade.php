@extends('layout.app')

@section('style')
<style>
    .modal-backdrop {
        background-color: transparent;
    }
    .modal-dialog { 
        top: 3rem !important; 
        margin-right: 10% !important;
    }
    .card:hover {
        scale: 1.1;
    }
    .formDevis {
        width: 50%;
    }
   
    @media (max-width: 768px) {
        .formDevis {
            width: 92%;
            margin: 0 4% 0;
        }
    }
</style>
@endsection

@section('content')

@include('layout.header')

<div style="background-color: lavender;  flex: 1;padding-top: 151px;">
    <div class="container py-4 align-items-center d-flex justify-content-center flex-column">
        <h3 class="text-center mb-4">
            DEMANDE DE DEVIS
        </h3> 
        <h5 class="text-center mb-4">
            ( {{ $offer->title }} )
        </h5>
        <form action="{{ route('devis.store', $offer->id) }}" method="post" class="formDevis py-4">
        @csrf
                <input type="hidden" name="offer" value="{{ $offer->id }}"/>
                <div class="form-outline mb-2 finput">
                    <input type="text" name="full_name" placeholder="Nom et Prénom *" value="{{ isset(auth()->user()->last_name) ? auth()->user()->first_name.' '.auth()->user()->last_name : '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="email" name="email" placeholder="Adresse mail *" value="{{ auth()->user()->email ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" name="phone" placeholder="Téléphone *" value="{{ auth()->user()->phone ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" name="entreprise" placeholder="Nom de la société" value="{{ auth()->user()->company_name ?? '' }}" class="form-control"/>
                </div>
                <div class="form-outline mb-2 finput">
                    <textarea name="message" cols="50" rows="7" placeholder="Votre message" class="form-control"></textarea>
                </div>
                <div class="align-items-center d-flex justify-content-center pt-2">
                    <button type="submit" class="btn btn-success" style="width: 150px;">
                            Envoyer
                    </button>
                </div>
        </form>
    </div>
</div>

@include('layout.footer')
@endsection

@section('javascript')
<script>
 
</script>
@endsection