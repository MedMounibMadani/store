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
              
           <p> Suite à la création de votre compte chez SAII. </p> <br>

           <p> Veuillez trouver ci-dessous votre code d'activation : </p> <br>

           <p style="font-size: 20px;"> <b> {{ $user->code }} </b> </p>

        </div>
    </td>
</tr>



@endsection