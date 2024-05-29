@extends('admin.dashboard')
@section('style')
<style>
   
</style>
@endsection
@section('content')
<div class="container mt-4">
    <div class="my-4">
        <a href="{{ route('messages') }}" class="btn btn-success"> Boite de réception </a>
    </div>
<div class="card">
  <h5 class="card-header">{{ $message->full_name }}</h5>
  <div class="card-body">
    <h5 class="card-title"> OFFRE : {{ $message->offer->title ?? '' }}</h5>
    <p class="card-text">
        Email : {{ $message->email }} <br>
        Tél : {{ $message->phone }} <br>
        Entreprise : {{ $message->entreprise ?? '' }} <br>
    </p>
    <p>
        Message <br>
        {{
            $message->message
        }}
    </p>
  </div>
</div>
</div>
@endsection
@section('javascript')
<script>

</script>
@endsection