@extends('layout.app')

@section('style')
<style>
    .modal-dialog { 
        top: 4rem !important; 
        margin-right: 10%;
    }
    .card:hover {
        scale: 1.1;
    }
    .finput {
        width: 320px;
    }
    #card-element {
        width: 100%;
    }
    #payment-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #card-element {
            border: 1px solid #ccc;
            padding: 12px;
            background: white;
            height: 46px !important;
            border-radius: 4px;
            margin-bottom: 10px;
            height: 40px; /* Set the desired height here */
        }
        #card-errors {
            color: red;
            margin-top: 10px;
        }

        #server-errors {
            color: red;
            margin-top: 10px;
        }
        
        @media (max-width: 768px) {
            .items-payment {
                flex-direction: column;
            }
            .payment-part-two {
                margin-top: 10px; 
            } 
        }
</style>
@endsection

@section('content')

@include('layout.header')

<div style="background-color: lavender;  flex: 1;padding-top: 151px;">
    <div class="container py-4">
        <h3 class="text-center mb-4">
            PAIEMENT
        </h3>
        <form id="payment-form">
        @csrf
        <div class="items-payment d-flex align-items-center justify-content-center pt-4">
            <div class="mx-4">
                <div class="form-outline mb-2 finput">
                    <input type="text" id="fname" name="first_name" placeholder="Prénom" value="{{ auth()->user()->first_name ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" id="lname" name="last_name" placeholder="Nom" value="{{ auth()->user()->last_name ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="email" id="email" name="email" placeholder="Adresse mail" value="{{ auth()->user()->email ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" id="phone" name="phone" placeholder="Téléphone" value="{{ auth()->user()->phone ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" id="address" name="address" placeholder="Adresse" value="{{ auth()->user()->address ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" id="city" name="city" placeholder="Ville" value="{{ auth()->user()->city ?? '' }}" class="form-control" required/>
                </div>
                <div class="form-outline mb-2 finput">
                    <input type="text" id="zipcode" name="zip_code" placeholder="Code postal" value="{{ auth()->user()->zip_code ?? '' }}"  class="form-control" required/>
                </div>
                <div class="form-outline mb-1 finput">
                    <input type="text" id="country" name="country" placeholder="Pays" value="{{ auth()->user()->country ?? '' }}" class="form-control" required/>
                </div>
            </div>
            <div class="payment-part-two mx-4 d-flex align-items-center justify-content-center flex-column" style="width: 350px;">
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
                <img src="{{ url('cb.png') }}" style="width: 295px; height: 104px;" alt="">
                <div class="finput" id="card-element"></div>
                <div id="card-errors" role="alert"></div>
                <div id="server-errors" role="alert"></div>
                <button type="submit" id="submitBtn" style="width: 350px; height: 45px;" class="btn btn-success mt-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mr-2 bi bi-credit-card-fill" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
                    </svg>
                    Payer
                </button>
            </div>
        </div>
        </form>
    </div>
</div>

@include('layout.footer')
@endsection

@section('javascript')
<script src="https://js.stripe.com/v3/"></script>
<script>
        document.addEventListener('DOMContentLoaded', async () => {
            const stripe = Stripe('pk_test_51PJGh5RpuU5NstUdQfyuSGI6EwIJPGPYiWj4KjEI4TzNYF44rkIm8gRekYNgAj5BI6G2eCbZvYXS0uI4RnJsbO0n00GX9wKpUZ', {
                locale: 'fr'
            });
            const submitBtn = document.getElementById('submitBtn');
            const appearance = {
                theme: 'stripe'
            };
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        iconColor: '#c4f0ff',
                        color: '#000',
                        backgroundColor: 'white',
                        border: '1px solid #ccc',
                        borderRadius: '4px',
                        padding: '0px',
                        fontWeight: '500',
                        fontFamily: 'sans-serif',
                        fontSize: '15px',
                        height: '40px',
                        fontSmoothing: 'antialiased',
                    },
                    invalid: {
                        iconColor: 'red',
                        color: 'red',
                    },
                },
            });
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>  Paiement';
                const email = document.getElementById('email').value;
                const first_name = document.getElementById('fname').value;
                const last_name = document.getElementById('lname').value;
                const phone = document.getElementById('phone').value;
                const address = document.getElementById('address').value;
                const zip_code = document.getElementById('zipcode').value;
                const city = document.getElementById('city').value;
                const country = document.getElementById('country').value;
                const serverErrors = document.getElementById('server-errors');

                const response = await fetch('/paiement/etape-trois', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(
                        {
                            email: email,
                        }
                    ),
                });

                const { clientSecret } = await response.json();

                const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { email: email },
                    },
                });

                if (error) {
                    document.getElementById('card-errors').textContent = error.message;
                    submitBtn.innerHTML = 'Payer';
                } else if (paymentIntent.status === 'succeeded') {
                    submitBtn.innerHTML = 'Payé !';
                    submitBtn.disabled = true;
                    const response = await fetch('/paiement/success', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(
                            {
                                email: email,
                                first_name: first_name,
                                last_name: last_name,
                                phone: phone,
                                address: address,
                                zip_code: zip_code,
                                city: city,
                                country: country,
                            }
                        ),
                    });
                    const { message, url } = await response.json();
                    if (message == 'done') {
                        window.location.href = url + '/paiement/success';
                    } else {
                        serverErrors.innerHTML = 'Paiement bien récu. <br>Une erreur s\'est produite lors du l\'enregistrement de votre commande. <br>Veuillez nous contacter svp.';
                    }
                }
            });
        });
    </script>
@endsection