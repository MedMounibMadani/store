@extends('layout.app')

@section('style')
<style>
    .modal-dialog { 
        top: 4rem !important; 
        margin-right: 10%;
    }
    .card:hover {
        scale: 1.05;
    }
    .saii-mark:hover {
        scale: 1.2;
    }
    .offer-item {
        width: 94%;
        margin: 0 3% 0;
        padding: 2%;
        height: 220px;
        color: white;
        display: flex;
        align-items: end;
        justify-content: space-between;
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
<div style="padding-top: 151px; background: linear-gradient(rgba(249, 249, 249, 0.9), rgba(249, 249, 249, 0.9)), url('login.jpg');background-size: cover; flex: 1; ">
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
                    <button class="mr-2" style="background-color: transparent; color: black;" onclick="add({{ $article->id }}, '{{ substr($article->name, 0, 12) . '..' }}', '{{ auth()->user() ? $article->priceWithDiscount() : $article->priceTtcWithDiscount() }}', {{ $article->discount }} )">
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
    <div class="my-4 py-4 mx-0" style="background-color: black;">
        <div class="d-flex align-items-center justify-content-center flex-column">
            <img src="{{ url('saii-logo.png') }}" class="img-fluid" height="150" width="150" alt="" style="filter: brightness(0) invert(1);">
            <div class="d-flex align-items-center justify-content-around flex-wrap mt-4" style="width: 80%; margin: 0 10% 0; color: white;">
                <div class="saii-mark d-flex align-items-center justify-content-center flex-column mx-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-file-lock-fill" viewBox="0 0 16 16">
                        <path d="M7 6a1 1 0 0 1 2 0v1H7zM6 8.3c0-.042.02-.107.105-.175A.64.64 0 0 1 6.5 8h3a.64.64 0 0 1 .395.125c.085.068.105.133.105.175v2.4c0 .042-.02.107-.105.175A.64.64 0 0 1 9.5 11h-3a.64.64 0 0 1-.395-.125C6.02 10.807 6 10.742 6 10.7z"/>
                        <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2m-2 6v1.076c.54.166 1 .597 1 1.224v2.4c0 .816-.781 1.3-1.5 1.3h-3c-.719 0-1.5-.484-1.5-1.3V8.3c0-.627.46-1.058 1-1.224V6a2 2 0 1 1 4 0"/>
                    </svg>
                    <b class="mt-3">PAIEMENT SÉCURISÉ</b>
                </div>
                <div class="saii-mark d-flex align-items-center justify-content-center flex-column mx-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                    </svg>
                    <b class="mt-3">LIVRAISON RAPIDE</b>
                </div>
                <div class="saii-mark d-flex align-items-center justify-content-center flex-column mx-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
                        <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                        <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.4 5.4 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2z"/>
                    </svg>
                    <b class="mt-3">SERVICE CLIENT</b>
                </div>
            </div>
        </div>
    </div> 
    @if( isset($offers) && count($offers) > 0 )
    <div class="container py-4">
        <h3 class="text-center pb-4"> EXPLORER NOS OFFRES </h3>
        <div class="owl-carousel">
            @foreach( $offers as $offer )
                <div class="mb-3 offer-item" style="background-size: cover !important; background-repeat: no-repeat !important; background-position: center; background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 50%, black), url('{{ $offer->getFirstMediaUrl('OfferImages') != '' ? $offer->getFirstMediaUrl('OfferImages') : url('login.jpg') }}')";">
                    <div>
                        <b>{{ $offer->title }}</b> <br>
                        <small> {{ $offer->description }} </small>
                    </div> 
                    <a href="{{ route('devis.get', $offer->id) }}" class="btn bg-light" style="min-width: 170px; color:black !important;"> Demander un devis </a> 
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
    <script>
        $(document).ready(function(){
          $(".owl-carousel").owlCarousel({
                    loop: true,  
                    margin: 10,  
                    autoplay: true,  
                    autoplayTimeout: 3000, 
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 1.1
                        },
                        750: {
                            items: 2.2
                        },
                        1500: {
                            items: 3.3
                        }
                    }
          });
        });
    </script>
@endsection