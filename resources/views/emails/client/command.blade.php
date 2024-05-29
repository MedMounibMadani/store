@extends('emails.base')

@section('content')



<tr>
    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:100px;word-break:break-word;">
        <div style="font-size: 20px;color: #000000;">

            Bonjour {{ $command->first_name .' '. $command->last_name }},

        </div>
    </td>
</tr>
<tr>
    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:50px;word-break:break-word;">
        <div style="font-family:helvetica;font-size:16px;line-height:1;text-align:left;color:#000000;">
              
           <p> Votre commande a été bien récu. </p> <br>
           <p> <b>Détails : </b> </p>
            @foreach( $command->articles as $article )
                <p class="mx-2">  <b> {{ $article->pivot->count }} X </b> {{ $article->name }} </p> <br>
            @endforeach
            <p> <b>Total payé : </b> {{ number_format( $command->checkout, 2, ',', ' ' ) }} € </p>

            <p>Merci et à bientôt.</p>
        </div>
    </td>
</tr>



@endsection