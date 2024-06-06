<div class="header-container">
    <div class="header">
        <a class="logo d-flex align-items-center justify-content-center" href="{{ route('welcome') }}">
            <img src="{{ url('saii-logo.png') }}"  height="80" width="80" alt="">
        </a>
        <div class="d-flex align-items-center justify-content-center">
            @if( ! auth()->user() )
                <a href="{{ route('login') }}" class="btn btn-light mx-4"> Se connecter </a>
            @else
                    <span class="d-none d-sm-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="mr-2 bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                        {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
                    </span>
                    <form action="{{ route('logout') }}" class="mx-4" method="post">
                        @csrf
                        <button class="btn btn-warning" type="submit">
                            <div class="d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-box-arrow-right mr-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                </svg>
                                Se déconnecter
                            </div>
                        </button>
                    </form> 
            @endif
            <div class="d-flex align-items-center justify-content-center" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#Panier">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
                    <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
                </svg>
                <div id="pocket" class="box-items-counter">
                    0
                </div>
            </div>
        </div>
    
        <div class="modal fade" id="Panier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-center flex-column">
                        <h4>Votre panier </h4>
                        <ol class="list-group list-group-numbered" id="ItemsList" style="width: 100%;">
                            
                        </ol>
                        <button type="button" class="btn btn-success mt-2 d-flex align-items-center justify-content-center" onclick="checkout('{{ env('APP_URL') }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mr-2 bi bi-credit-card-fill" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
                            </svg>
                            Passer au paiement
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-primary" style="background-color: black;" >
    <div class="d-flex align-items-center justify-content-around" style="margin: 0 15% 0; width: 70%;" >
        <a class="navbar-brand" style="color: white; font-weight: bold;" href="{{ route('welcome') }}">Acceuil</a>
        <button class="navbar-toggler" style="background-color: white;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <div class="d-block d-sm-none mb-4">
                <div class="d-flex justify-content-end">
                    <a style="font-weight: bold;" href="{{ route('welcome') }}">Fermer</a>
                </div>
            </div>
            @foreach( $categories as $category )
                <a class="navbar-brand mx-4 linksMobile" style="color: white; font-weight: bold;" href="{{ route('welcome', ['categorie' => $category->slug ] ) }}"> {{ strtoupper($category->name) }} </a>
            @endforeach
        </div>
    </div>
    </nav>
</div>