@extends('emails.base')

@section('content')



<tr>
    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:100px;word-break:break-word;">
        <div style="font-size: 18px;color: #000000;">

            Cher(e) {{ $command->first_name .' '. $command->last_name }},

        </div>
    </td>
</tr>
<tr>
    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:50px;word-break:break-word;">
        <div style="font-family:helvetica;font-size:16px;line-height:1;text-align:left;color:#000000;">
              
           <p> Nous vous remercions pour votre commande chez <b>SAII</b>. <br>
           <p> Nous somme ravis de vous confirmer que votre commande n°<b>{{ $command->id }}</b> a été bien récu le {{ \Carbon\Carbon::parse($command->created_at)->format('d/m/Y') }}. </p> <br>
           <p> Voici un récapitulatif de votre commande :  </p> <br>
            @foreach( $command->articles as $article )
                <p class="mx-2">  <b> {{ $article->pivot->count }} X </b> {{ $article->name }} </p> <br>
            @endforeach
            <p> <b>Total payé : </b> {{ number_format( $command->checkout, 2, ',', ' ' ) }} € </p> <br>
            <p> <b>Adresse de livraison : </b> {{ $command->delivery ? $command->address . ', ' . $command->zip_code . ' ' . $command->city . ' ' . $command->country : 'À récupérer en magasin, 662 Rue des Jonchères 69730 Genay FRANCE' }}  </p> <br>
            @if ( $command->delivery && $command->delivery_date )
            <p> <b>Date de livraison estimée : </b> avant le {{ \Carbon\Carbon::parse($command->delivery_date)->format('d/m/Y') }}.  </p> <br>
            @endif
            <p class="mt-4">
            Prochaines étapes : <br>
            Traitement de votre commande : Votre commande est actuellement en cours de traitement et sera bientôt expédiée. <br>
            Besoin d'aide ? <br>
            Si vous avez des questions ou des préoccupations concernant votre commande, n'hésitez pas à nous contacter : <br>

            Email : contact.osman@saii.fr <br>
            Téléphone : +33 7 78 25 77 15 <br>

            Nous vous remercions encore une fois pour votre achat et votre confiance en SAII. Nous espérons que vous apprécierez nos produits. <br>
            </p>
            <p>Merci et à bientôt.</p>
        </div>
    </td>
</tr>



@endsection