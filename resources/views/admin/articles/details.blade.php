@extends('layout.app')

@section('style')
<style>
    .modal-dialog { 
        top: 4rem !important; 
        margin-right: 10%;
    }
    .article-image img {
        width: 368px !important;
        height: 368px;
        object-fit: contain;
    }

</style>
@endsection

@section('content')
@include('layout.header')
<div style="background-color: lavender; padding-top: 151px;">
<div class="container pt-4">
    <div class="row py-4">
        <div class="col-md-6 col-sm-12 d-flex align-items-center justify-content-center">
            <div class="owl-carousel">
                @foreach ($article->getMedia('ArticleImages') as $item)
                <div class="article-image">
                    <img src="{{ $item->getUrl() }}" alt="image"> 
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            @if ( $article->discount > 0 )
                <div class="mb-2 bg-danger d-flex align-items-center justify-content-center" style="height: 34px; width: 85px; color: white; font-weight: bold; font-size: 28px;">
                    -{{ $article->discount }}%
                </div>
            @endif
            <h2>
                <div class="mb-2 bg-primary d-flex align-items-center justify-content-center" style="height: 34px; width: fit-content; padding: 0 20px 0; color: white; font-weight: bold; font-size: 28px;">
                    {{ $article->category->name ?? '' }}
                </div> {{ $article->name }}
            </h2>
            <span class="d-flex align-items-center justify-content-start mb-4">
                @if ( $article->count > 5 ) 
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="color: green;" class="mr-2 bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg> en stock. 
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="color: red;" class="mr-2 bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                    </svg>
                    {{ $article->count }} derniers articles.
                @endif
            </span>
            <p>
                {!! nl2br(e($article->description)) !!}
            </p>
            <div class="mb-4">
                <b> {{ $article->days_to_delivery > 0 ? ' Livraison avant le ' . \Carbon\Carbon::now()->addDays($article->days_to_delivery)->format('d/m/Y') . '.' : '' }} </b>  
            </div>
            <h4>
                <div class="d-flex" style="font-weight: bold;">
                    @auth
                        <div class="mr-2"> Prix HT : </div>
                        @if ($article->discount > 0)
                        <div class="d-flex">
                            <div style="text-decoration-line: line-through; margin-right: 10px; color: red;">
                                {{ number_format( round( $article->price) , 2, ',', ' ' ) . '€' }}
                            </div>
                                {{ $article->priceWithDiscount() . '€' }}
                        </div>
                        @else
                            {{ number_format( round( $article->price) , 2, ',', ' ' ) . '€' }}
                        @endif
                    @endauth
                    @guest
                        <div class="mr-2"> Prix TTC : </div> 
                        @if ($article->discount > 0)
                        <div class="d-flex">
                            <div style="text-decoration-line: line-through; margin-right: 10px; color: red;">
                                {{ $article->priceTtc() . '€' }}
                            </div>
                                {{ $article->priceTtcWithDiscount() . '€' }}
                        </div>
                        @else
                            {{ $article->priceTtc() . '€' }}
                        @endif
                    @endguest     
                </div>  
            </h4>
           <div class="d-flex align-items-center justify-content-center mt-4">
                <button class="btn btn-warning" onclick="add({{ $article->id }}, '{{ substr($article->name, 0, 12) . '..' }}', '{{ auth()->user() ? $article->priceWithDiscount() : $article->priceTtcWithDiscount() }}', {{ $article->discount }} )"> Ajouter au panier </button>
           </div>
        </div>
    </div>
    @if ( count($others) > 0 )
    <div class="py-4">
        <h3 class="text-center">
            Ce(s) article(s) peuvent également vous intéresser
        </h3>
        <div class="mt-4 d-flex align-items-center justify-content-center flex-wrap">
            @foreach( $others as $art )
            <div class="card my-2 mx-2" style="width: 18rem;">
                @if ( $art->discount > 0 )
                    <div class="bg-danger d-flex align-items-center justify-content-center" style="position: absolute; top: 0; right: 0; height: 24px; width: 55px; color: white; font-weight: bold;">
                        - {{ $art->discount }} %
                    </div>
                @endif
                <img src="{{ $art->getFirstMediaUrl('ArticleImages') != '' ? $art->getFirstMediaUrl('ArticleImages') : url('default.png') }}" height="200" style="object-fit: cover;" class="card-img-top" alt="article">
                <div class="card-body">
                    <h5 class="card-title"> {{ $art->name }} </h5>
                    <div style="font-weight: bold; padding-bottom: 36px;">
                    @auth
                        @if ($art->discount > 0)
                            <div class="d-flex">
                                <div style="text-decoration-line: line-through; margin-right: 10px; color: red;">
                                    {{ number_format( round( $art->price) , 2, ',', ' ' ) . '€' }}
                                </div>
                                    {{ $art->priceWithDiscount() . '€ HT' }}
                            </div>
                        @else
                            {{ number_format( round( $art->price) , 2, ',', ' ' ) . '€ HT' }}
                        @endif
                    @endauth
                    @guest
                        @if ($art->discount > 0)
                            <div class="d-flex">
                                <div style="text-decoration-line: line-through; margin-right: 10px; color: red;">
                                    {{ $art->priceTtc() . '€' }}
                                </div>
                                    {{ $art->priceTtcWithDiscount() . '€ TTC' }}
                            </div>
                        @else
                            {{ $art->priceTtc() . '€ TTC' }}
                        @endif
                    @endguest
                    </div>  
                    <div class="d-flex align-items-center justify-content-end" style="position: absolute; bottom: 10px; right: 10px;">
                        <button class="mr-2" style="background-color: transparent; color: black;" onclick="add({{ $art->id }}, '{{ substr($art->name, 0, 12) . '..' }}', '{{ auth()->user() ? $art->priceWithDiscount() : $art->priceTtcWithDiscount() }}', {{ $art->discount }} )">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-bag-plus-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0M8.5 8a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V12a.5.5 0 0 0 1 0v-1.5H10a.5.5 0 0 0 0-1H8.5z"/>
                            </svg>
                        </button>
                        <a href="{{ route('articles.details', ['id' => $art->id] ) }}" class="btn bg-primary" style="color: white;">Détails</a>
                    </div>             
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
</div>
@include('layout.footer')
@endsection

@section('javascript')
<script>
    $(document).ready(function(){
      $(".owl-carousel").owlCarousel({
                items: 1,  
                loop: true,  
                margin: 10,  
                autoplay: true,  
                autoplayTimeout: 4000, 
                autoplayHoverPause: true 
      });
    });
</script>
@endsection