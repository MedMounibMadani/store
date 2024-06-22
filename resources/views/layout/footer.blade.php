<footer>
    <div class="footerLogo" style="color: white; scale: 2;">
        <a class="logo d-flex align-items-center justify-content-center" href="{{ route('welcome') }}">
            <img src="{{ url('saii-logo.png') }}" class="img-fluid" height="100" width="100" alt="">
        </a>
    </div>
    <div style="color: white;">
        <ul style="list-style-type: none; padding-left: 0px;">
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
            662 Rue des Jonchères 69730 Genay FRANCE
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
            &copy; {{ \Carbon\Carbon::now()->year }} MdnMounib
        </a> 
    </div>
</footer>