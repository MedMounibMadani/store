@extends('admin.dashboard')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form enctype="multipart/form-data" action="{{ isset($offer) ? route('offers.update', ['id' => $offer->id] ) : route('offers.store') }}" method="POST">
        @csrf
        @if( isset($offer) )
            @method('PUT')
        @endif
        <h2>
            {{ isset($offer) ? 'Modifier' : 'Ajouter' }} une offre
        </h2>
        <div class="my-4">
            <label class="form-label">Titre</label>
            <input name="title" type="text" value="{{ $offer->title ?? '' }}" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label> <br>
            <textarea name="description" cols="30" rows="4" class="form-control">{{ $offer->description ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo</label> <br>
            @if(isset($offer) && isset($offer->getMedia('*')[0]))
                <a role="button" data-bs-toggle="modal" data-bs-target="#ImG{{ $offer->id }}">
                    <img src="{{ $offer->getFirstMediaUrl('OfferImages') }}" width="100" class="my-2" alt="image"> 
                </a>
                <div class="modal fade" id="ImG{{ $offer->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <img src="{{ $offer->getFirstMediaUrl('OfferImages') }}" alt="image"> 
                        <a class="btn btn-danger" href="{{ route('offers.media.delete', ['id' => $offer->id ] ) }}">
                            Supprimer
                        </a>
                    </div>
                </div>
                </div>
            @endif
            <input type="file" class="form-control" name="image" />
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>       
        <button type="submit" class="btn btn-success">{{ isset($offer) ? 'Sauvegarder' : 'Ajouter' }}</button>
    </form>
</div>
@endsection