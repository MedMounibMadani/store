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
          @if ( session()->has('message') )
            <div class="alert alert-info alert-dismissible fade show" role="alert" style="font-size: 12px;">
              {{ session()->get('message') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
        <h3 class="pb-4 text-center">CONNEXION</h3>
        <form method="POST" action="{{ route('admin.login') }}" class="d-flex flex-column justify-content-center align-items-center">
          @csrf
          <!-- Email input -->
          @if ( session()->has('error') )
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px;">
              {{ __('Ces identifiants ne correspondent pas à aucun utilisateur.') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          <div data-mdb-input-init class="form-outline mb-4 finput">
            <input type="email" id="form2Example1" name="email" placeholder="Login" class="form-control" required/>
          </div>
          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-4 finput">
            <input type="password" id="form2Example2" name="password" placeholder="Mot de passe" class="form-control" required/>
          </div>
          <!-- Submit button -->
          <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Se connecter</button>
        </form>
        <p class="text-center">
          <a href="{{ route('password.forgot') }}">Mot de passe oublié ?</a>
        </p>
        <p class="text-center">
          Nouveau chez SAII ?
        </p>
        <div class="text-center">
        <a href="{{ route('register') }}" class="btn btn-warning">
          Créer votre compte
        </a>
        </div>
      </div>
    </div>   
  </div>
</div>


@endsection