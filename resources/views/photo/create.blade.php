@extends('layouts.main')

@section('content')

    <div class="main-content" style="min-height: 762px;">
        <section class="section">
            <div class="section-header">
                <h1>{{ $heading }}</h1>

            </div>

            <div class="section-body">
                <h2 class="section-title">{{ $heading }}</h2>

                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><a href="{{ route('albums.show', [$album->slug]) }}">Retour à l'album</a></h4>
                            </div>
                            <div class="card-body">

                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form action="{{ route('photos.store', [$album->slug]) }}" method="post" class="withFile" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="title">Titre</label>
                                        <input type="text" name="title" value="{{ old('title') }}" class="form-control">
                                        @error('title')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <input type="file" name="photo" class="form-control">
                                        @error('photo')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div style="display: none;" class="progress" id="progress">
                                        <div id="progressbar" class="progress-bar" role="progressbar" style="width: 0%;">0%</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tags">Tags séprarés par des virgules</label>
                                        <input type="text" name="tags" value="{{ old('tags') }}" placeholder="lac, tigre, avion" class="form-control">
                                        @error('tags')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Upload</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
        </section>
    </div>

@stop