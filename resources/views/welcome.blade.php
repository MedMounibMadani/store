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
        scale: 1.05;
    }
    @media (max-width: 768px) {
        .articleCard {
            width: 45% !important;
        }
        .articleCard img {
            height: 100px !important;
        }
        .articleCard h5 {
            font-size: 15px !important;
        }
        .articlePrice {
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-size: 14px !important;
        }
    }
</style>
@endsection

@section('content')

@include('layout.header')
<div style="background: linear-gradient(rgba(249, 249, 249, 0.9), rgba(249, 249, 249, 0.9)), url('login.jpg');background-size: cover; flex: 1; ">
    @if ( session()->has('commandSuccess') )
        <div class="container pt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 18px;">
                {{ session()->get('commandSuccess') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if ( session()->has('messageSent') )
        <div class="container pt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 18px;">
                {{ session()->get('messageSent') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="container text-center pt-4">
        <h3>{{ $articles->total() }} article(s) disponible(s)</h3> <br>
        <form class="d-flex" action="{{ route('articles.search') }}" method="post">
            @csrf
            <input class="form-control me-2" name="search" type="search" placeholder="Article" aria-label="Search" required>
            <button class="btn btn-warning" type="submit">RECHERCHER</button>
        </form>
    </div>
    <div class="container d-flex justify-content-center flex-wrap py-4">
        @foreach( $articles as $article )
        <div class="card articleCard my-2 mx-2" style="width: 18rem;">
            @if ( $article->discount > 0 )
                <div class="bg-danger d-flex align-items-center justify-content-center" style="position: absolute; top: 0; right: 0; height: 24px; width: 55px; color: white; font-weight: bold;">
                    - {{ $article->discount }} %
                </div>
            @endif
            <img src="{{ $article->getFirstMediaUrl('ArticleImages') != '' ? $article->getFirstMediaUrl('ArticleImages') : url('default.png') }}" height="200" style="object-fit: cover;" class="card-img-top" alt="article">
            <div class="card-body">
                <h5 class="card-title"> {{ $article->name }} </h5>
                <div style="font-weight: bold; padding-bottom: 36px;">
                @auth
                    @if ($article->discount > 0)
                        <div class="d-flex articlePrice">
                            <div style="text-decoration-line: line-through; margin-right: 10px; color: red;">
                                {{ number_format( round( $article->price) , 2, ',', ' ' ) . '€' }}
                            </div>
                                {{ $article->priceWithDiscount() . '€ HT' }}
                        </div>
                    @else
                           {{ number_format( round( $article->price) , 2, ',', ' ' ) . '€ HT' }}
                    @endif
                @endauth
                @guest
                    @if ($article->discount > 0)
                        <div class="d-flex articlePrice">
                            <div style="text-decoration-line: line-through; margin-right: 10px; color: red;">
                                {{ $article->priceTtc() . '€' }}
                            </div>
                                {{ $article->priceTtcWithDiscount() . '€ TTC' }}
                        </div>
                    @else
                           {{ $article->priceTtc() . '€ TTC' }}
                    @endif
                @endguest
                </div>  
                <div class="d-flex align-items-center justify-content-end" style="position: absolute; bottom: 10px; right: 10px;">
                    <button class="mr-2" onclick="add({{ $article->id }}, '{{ substr($article->name, 0, 12) . '..' }}', '{{ auth()->user() ? $article->priceWithDiscount() : $article->priceTtcWithDiscount() }}', {{ $article->discount }} )">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-bag-plus-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0M8.5 8a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V12a.5.5 0 0 0 1 0v-1.5H10a.5.5 0 0 0 0-1H8.5z"/>
                        </svg>
                    </button>
                    <a href="{{ route('articles.details', ['id' => $article->id] ) }}" class="btn bg-primary" style="color: white;">Détails</a>
                </div>             
            </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-center">
                {!! $articles->links() !!}
            </div>
        </div>
    </div>
    @if( isset($offers) && count($offers) > 0 )
    <div class="container py-4">
        <h3 class="text-center pb-4"> EXPLORER NOS OFFRES </h3>
        <div class="d-flex justify-content-center flex-wrap">
        @foreach( $offers as $offer )
        <div class="col-sm-12 col-md-6 col-lg-4 m-2">
            <div class="card mb-3">
                <img src="{{ $offer->getFirstMediaUrl('OfferImages') != '' ? $offer->getFirstMediaUrl('OfferImages') : url('login.jpg') }}" height="200" style="object-fit: cover;" class="card-img-top" alt="offre">
                <div class="card-body">
                    <h5 class="card-title">{{ $offer->title }}</h5>
                    <p class="card-text"> {{ $offer->description }} </p>
                    <p class="d-flex justify-content-end"> <a href="{{ route('devis.get', $offer->id) }}" class="btn btn-success"> Demander un devis </a> </p>
                </div>
            </div>
        </div>
        @endforeach
        </div>
    </div>
    @endif
</div>
@include('layout.footer')
@endsection

@section('javascript')
    @if ( session()->has('commandSuccess') )
    <script>
       clearSession()
    </script>
    @endif
@endsection