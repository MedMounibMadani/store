@extends('layout.app')

@section('style')
<style>
    .modal-backdrop {
        background-color: transparent;
    }
    .modal-dialog { 
        top: 3rem !important; 
        margin-right: 10% !important;
    }
    .card:hover {
        scale: 1.1;
    }
    .finput {
        width: 320px;
    }
</style>
@endsection

@section('content')

@include('layout.header')

<div style="background-color: lavender;  flex: 1;">
    <div class="container py-4">
        <h3 class="text-center mb-4">
            Vos coordonnées
        </h3>
        <!-- action="{{ route('step.three') }}" method="post" -->
        <form id="payment-form" >
        @csrf
        <div class="d-flex align-items-center justify-content-center pt-4">
            <div class="mx-4">
                <div class="form-outline mb-2 finput">
                    <input type="text" name="first_name" placeholder="Prénom" value="{{ auth()->user()->first_name ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" name="last_name" placeholder="Nom" value="{{ auth()->user()->last_name ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="email" id="email" name="email" placeholder="Adresse mail" value="{{ auth()->user()->email ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" name="phone" placeholder="Téléphone" value="{{ auth()->user()->phone ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" name="address" placeholder="Adresse" value="{{ auth()->user()->address ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" name="city" placeholder="Ville" value="{{ auth()->user()->city ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" name="zip_code" placeholder="Code postal" value="{{ auth()->user()->zip_code ?? '' }}"  class="form-control" required/>
                </div>
                <div class="form-outline mb-1 finput">
                    <input type="text" name="country" placeholder="Pays" value="{{ auth()->user()->country ?? '' }}" class="form-control" required/>
                </div>
            </div>
            <div class="mx-4 d-flex align-items-center justify-content-center flex-column">
                @auth
                    <div class="d-flex align-items-center justify-content-between" style="width: 295px;">
                        <b>
                            Total HT
                        </b>
                        <h4>
                            {{ number_format( round( $totalHT ) , 2, ',', ' ' ) }}€
                        </h4>
                    </div>                  
                @endauth
                    <div class="d-flex align-items-center justify-content-between mb-4" style="width: 295px;">
                        <b>
                            Total TTC à régler
                        </b>
                        <h4>
                            {{ number_format( round( $totalTTC ) , 2, ',', ' ' ) }}€
                        </h4>
                    </div>     
                <img src="{{ url('cb.jpg') }}" style="width: 295px; height: 104px;" alt="">
                <button type="submit" style="width: 295px; height: 70px;" class="btn btn-success mt-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mr-2 bi bi-credit-card-fill" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
                    </svg>
                    Confirmer et payer
                </button>
            </div>
        </div>
        <div id="card-element"></div>
        <div id="card-errors" role="alert"></div>
        </form>
    </div>
</div>

@include('layout.footer')
@endsection

@section('javascript')
<script src="https://js.stripe.com/v3/"></script>
<script>
        document.addEventListener('DOMContentLoaded', async () => {
            const stripe = Stripe('pk_test_51PJGh5RpuU5NstUdQfyuSGI6EwIJPGPYiWj4KjEI4TzNYF44rkIm8gRekYNgAj5BI6G2eCbZvYXS0uI4RnJsbO0n00GX9wKpUZ');
            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const email = document.getElementById('email').value;

                const response = await fetch('/paiement/etape-trois', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email }),
                });

                const { clientSecret } = await response.json();

                const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { email: email },
                    },
                });

                if (error) {
                    // Show error to your customer (e.g., insufficient funds)
                    document.getElementById('card-errors').textContent = error.message;
                } else if (paymentIntent.status === 'succeeded') {
                    // The payment has been processed!
                    console.log('Payment successful!');
                }
            });
        });
    </script>
@endsection