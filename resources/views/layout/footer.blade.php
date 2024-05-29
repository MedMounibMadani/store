<footer>
    <div class="footerLogo" style="color: white; scale: 2;">
        <a class="logo d-flex align-items-center justify-content-center" href="{{ route('welcome') }}">
            <img src="{{ url('saii-logo.png') }}" class="img-fluid" height="100" width="100" alt="">
        </a>
    </div>
    <div style="color: white;">
        <ul style="list-style-type: none;">
            @foreach( $categories as $cat )
                <li >
                   <h6>
                    <a href="{{ route('welcome', ['categorie' => $cat->slug ] ) }}">
                        {{ strtoupper($cat->name) }} 
                    </a> 
                    </h6>
                </li>
            @endforeach
        </ul>
    </div>
    <div style="color: white;">
        <p>
            <b> Adresse </b> <br>
            214 Rue de l'Artisanat, 01390 Saint-André-de-Corcy
        </p>
        <p>
            <b> Email </b> <br>
            contact.osman@saii.fr
        </p>
        <p>
            <b> Téléphone </b> <br>
            +33 7 78 25 77 15
        </p>
    </div>
    <div style="color: white;">
        Created By 
        <a href="https://tn.linkedin.com/in/med-mounib-madani-0b26b911a/fr" class="d-flex align-items-center justify-content-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-c-circle" viewBox="0 0 16 16">
        <path d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.146 4.992c-1.212 0-1.927.92-1.927 2.502v1.06c0 1.571.703 2.462 1.927 2.462.979 0 1.641-.586 1.729-1.418h1.295v.093c-.1 1.448-1.354 2.467-3.03 2.467-2.091 0-3.269-1.336-3.269-3.603V7.482c0-2.261 1.201-3.638 3.27-3.638 1.681 0 2.935 1.054 3.029 2.572v.088H9.875c-.088-.879-.768-1.512-1.729-1.512"/>
        </svg>
        MdnMounib
        </a> 
    </div>
</footer>