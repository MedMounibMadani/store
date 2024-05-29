@extends('layout.app')

@section('style')
<style>
.content {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: stretch;
}
.content h1 {
  font-size: 18px;
  font-weight: bold;
}
.loginPhoto, .loginContent {
  width: 50%;
  flex: 1 !important;
  box-sizing: border-box !important;
}
.loginPhoto {
  background-color: #63917f !important;
}
.loginPhoto img {
  width: 100%;
  height: 100%;
  opacity: 0.5;
}
.loginContent {
  height: 100% !important;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}
.card {
    box-shadow: 20px -16px #63917f;
}
.form-control {
  border-radius: 21px !important;
}
.finput {
    width: 80%;
}
@media (max-width: 768px) {
  .loginCard {
    width: 88% !important; 
    margin: 0 6% 0;
  }
}
</style>
@endsection

@section('content')
<div class="content">
  <div class="loginPhoto d-none d-sm-block">
    <img src="{{ url('login.jpg') }}" alt="">
  </div>
  <div class="loginContent">
    <div class="card loginCard" style="width: 50%;">
      <div class="card-body p-4">
        <h3 class="pb-2 text-center">Renouvellement du mot de passe</h3>
        <form method="POST" action="{{ route('post.reset') }}" class="d-flex flex-column justify-content-center align-items-center">
          @csrf
          <!-- Email input -->
          @if ( session()->has('error') )
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px;">
              {{ __('Veuillez vérifier vos données !') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          <!-- Password input -->
          <input type="hidden" name="user" value="{{ $user->id }}"/>
          @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
          @enderror
          <div class="form-outline mb-1 finput">
            <input type="password" name="password" placeholder="Nouveau mot de passe" class="form-control" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="password" name="password_confirmation" placeholder="Ressaisissez le mot de passe" class="form-control" required/>
          </div>
          <!-- Submit button -->
          <button type="submit" data-m1db-button-init data-mdb-ripple-init class="btn btn-primary btn-block mt-2">S'enregistrer</button>
        </form>
      </div>
    </div>   
  </div>
</div>


@endsection