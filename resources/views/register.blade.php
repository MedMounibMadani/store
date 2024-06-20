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
        <h3 class="pb-2 text-center">Créer votre compte</h3>
        <h6 class="pb-4 text-center">Pour les professionnels</h6>
        @if( $errors->any() )
          <div class="alert alert-danger">
            <ul>
              @foreach($errors->all() as $error)
                <li> {{ $error }} </li>
              @endforeach
            </ul>
          </div>
        @endif
        <form method="POST" action="{{ route('post.register') }}" class="d-flex flex-column justify-content-center align-items-center">
          @csrf
          <!-- Email input -->
          <div class="form-outline mb-1 finput">
            <input type="text" name="first_name" placeholder="Prénom" class="form-control" value="{{ old('first_name') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="text" name="last_name" placeholder="Nom" class="form-control" value="{{ old('last_name') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="text" name="company_name" placeholder="Nom de la société" class="form-control"  value="{{ old('company_name') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="string" name="siret" minlength="14" maxlength="14" placeholder="N° SIRET" class="form-control" value="{{ old('siret') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="email" name="email" placeholder="Adresse mail" class="form-control" value="{{ old('email') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="text" name="phone" placeholder="Téléphone" class="form-control" value="{{ old('phone') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="text" name="address" placeholder="Adresse" class="form-control" value="{{ old('address') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="text" name="city" placeholder="Ville" class="form-control" value="{{ old('city') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="text" name="zip_code" placeholder="Code postal" class="form-control" value="{{ old('zip_code') }}" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="text" name="country" placeholder="Pays" class="form-control" value="{{ old('country') }}" required/>
          </div>
          <!-- Password input -->
          <div class="form-outline mb-1 finput">
            <input type="password" name="password" placeholder="Mot de passe" class="form-control" required/>
          </div>
          <div class="form-outline mb-1 finput">
            <input type="password" name="password_confirmation" placeholder="Ressaisissez le mot de passe" class="form-control" required/>
          </div>
          <!-- Submit button -->
          <button type="submit" data-m1db-button-init data-mdb-ripple-init class="btn btn-primary btn-block mt-2">S'enregistrer</button>
        </form>
        <div class="mt-2 d-flex flex-column justify-content-center align-items-center">
          <a href="{{ route('login') }}" class="btn btn-warning">
            Vous avez déjà un compte ?
          </a>
        </div>
      </div>
    </div>   
  </div>
</div>


@endsection