@extends('emails.base')

@section('content')



<tr>
    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:100px;word-break:break-word;">
        <div style="font-size: 20px;color: #000000;">

            Bonjour {{ $user->first_name .' '. $user->last_name }},

        </div>
    </td>
</tr>
<tr>
    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:50px;word-break:break-word;">
        <div style="font-family:helvetica;font-size:16px;line-height:1;text-align:left;color:#000000;">
              
           <p> Suite à votre demande de renouvellement du mot de passe, veuillez cliquer sur le bouton ci-dessous. </p> <br>

            <a href="{{ $url }}" class="btn btn-warning"> Nouveau mot de passe </a> <br>
            
            <p> NB: ce lien expire dans 60mn. </p>

            <p class="mt-4">Si vous rencontrez des problèmes au niveau du bouton, cliquer directement sur ce lien : </p> <br>

            <a href="{{ $url }}"> {{ $url }} </a> <br>

        </div>
    </td>
</tr>



@endsection