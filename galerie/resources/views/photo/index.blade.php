@extends('layout.main')

@section('content')
    @if ($errors->any())
        <div class="container w-50 text-center alert alert-danger" id="errorsMessage">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    @if (session('success'))
        <div class="container w-50 text-center alert alert-success" id="successMessage">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    <div class="container w-50 mb-5">
        <form action="{{ route('store') }}" method="post" id="form" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Titre de la photo</label>
                <input type="text" name="title" class="form-control" id="" value="{{ old('title') }}">
            </div>
            <div class="form-group mt-2">
                <label for="photo">Choisir une photo</label>
                <input type="file" name="photo">
            </div>
            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
        </form>
    </div>
    <div class="container text-center">
        <div class="row g-lg-3">
            @forelse ($photos as $photo)
                <div class="col-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ $photo->url }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ $photo->thumb_url }}" class="card-img-top" alt="...">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $photo->title }}</h5>
                            <p class="card-text">Postée {{ $photo->created_at->diffForHumans() }}</p>
                            <div class="row">
                                <div class="col">
                                    <form action="{{ route('download', $photo->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i>{{$photo->convertToMo($photo->size)}}</button>
                                    </form>
                                </div>
                                <div class="col">
                                    <form action="{{ route('destroy', $photo->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>Aucune photos disponible</p>
            @endforelse
                {{$photos->links()}}
        </div>
    @endsection
