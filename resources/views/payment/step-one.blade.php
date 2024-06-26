@extends('layout.app')

@section('style')
<style>
    .modal-dialog { 
        top: 4rem !important; 
        margin-right: 10%;
    }
    .card:hover {
        scale: 1.1;
    }
    .form-check-input[type=radio] {
            border: 1px solid;
    }
    @media (max-width: 768px) {
        .article-item-pay {
            flex-direction: column;
        }
        .article-item-price {
            scale: 1.2 !important;
            margin: 12px 0 10px;
            transform: translate(0,0) !important;
        }
        .option-card-pay {
            flex-direction: column;
        }
        .option-card-pay * {
            font-size: 14px !important;
            scale: 1 !important;
        }
        .fees-mobile {
            margin-top: 7px;
        }
    }
</style>
@endsection

@section('content')

@include('layout.header')

<div style="background-color: lavender;   flex: 1;padding-top: 151px;">
    <div class="container py-4">
        <h3 class="text-center mb-4">
            VOTRE COMMANDE
        </h3>
        <form action="{{ route('step.two') }}" method="post">
            @csrf
            <ul class="list-group">
            @foreach ( $articles as $article )
            <li class="article-item-pay list-group-item d-flex justify-content-between align-items-center px-4 my-2" style="min-height: 120px;" >
                <div class="d-flex justify-content-center align-items-center">
                    <img src="{{ $article->getFirstMediaUrl('ArticleImages') }}" height="100" style="object-fit: cover; width: 100px !important;" class="card-img-top mr-2" alt="article">
                    <b> {{ strtoupper($article->name) }} </b>
                </div>    
                <div class="article-item-pay d-flex justify-content-center align-items-center">
                    <span class="article-item-price badge bg-primary rounded-pill" style="scale: 1.5; transform: translate(-30px, 0);">
                        @auth
                            {{ $article->priceWithDiscount() . '€ P.U' }}
                        @endauth
                        @guest
                            {{ $article->priceTtcWithDiscount() . '€ P.U' }}
                        @endguest
                    </span>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text" id="inputGroup-sizing-lg">Nombre d'articles</span>
                        <input type="number" style="width: 75px;" name="articles[{{ $article->id }}]" value="1" max="{{ $article->count }}" min="1" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
                    </div>
                </div>    
            </li>
            @endforeach
        
            <li class="option-card-pay list-group-item d-flex justify-content-between align-items-center px-4" style="min-height: 70px;" >
                <div class="d-flex">
                    <h5>Inclure la livraison ?</h5>
                    <div class="form-check">
                        <input class="form-check-input mx-2" type="radio" name="delivery" value="1" id="delivery">
                        <label class="form-check-label" for="delivery">
                            Oui
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input mx-2" type="radio" name="delivery" value="0" id="nodelivery">
                        <label class="form-check-label" for="nodelivery">
                            Non
                        </label>
                    </div>
                </div>
                <div>
                    @php
                    $deliveryDates = [];
                    foreach ( $articles as $article ) {
                        $articleDelivery = $article->days_to_delivery;
                        if ( ! in_array($articleDelivery, $deliveryDates) ) {
                            array_push($deliveryDates, $articleDelivery);
                        }
                    }
                    @endphp
                    @if ( max($deliveryDates) > 0 )
                        <h5>Livraison avant le {{ \Carbon\Carbon::now()->addDays( max($deliveryDates) )->format('d/m/Y') }} </h5>
                    @endif
                </div>
                <div class="fees-mobile d-flex justify-content-center align-items-center">
                    <b class="mx-4"> Frais de livraison </b>
                    <span class="badge bg-primary rounded-pill" style="scale: 1.5;">
                        @php 
                            $delivery = [];
                            foreach($articles as $delFee)
                            {
                                array_push($delivery, $delFee->delivery_fees);
                            }
                            echo max($delivery) . '€';
                        @endphp
                    </span>
                    <input type="hidden" name="deliveryFee" value="{{ max($delivery) }}">
                </div>    
            </li>        
            @error('delivery')
                <div class="row">
                    <div class="alert alert-danger mb-2" style="width: 90%; margin: 10px 5% 0;" role="alert">
                        Veuillez choisir à inclure la livraison ou non. 
                    </div>    
                </div>
            @enderror
            <li class="option-card-pay list-group-item px-4">
                <div class="form-outline my-1 finput">
                    <input type="text" name="message" placeholder="Ajouter un note ( demande / préférence.. )" class="form-control" value="{{ old('message') }}"/>
                </div>
            </li>    
            </ul>
        <div class="mt-4 d-flex justify-content-center align-items-center">
            <button type="submit" class="btn btn-success" style="width: 150px;"> Suivant </button>
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