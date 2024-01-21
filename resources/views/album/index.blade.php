@extends('layouts.main')

@section('content')

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>
              {{ $heading }}
            </h1>
            {{-- 
              <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Components</a></div>
              <div class="breadcrumb-item">Article</div>
            </div> 
            --}}
          </div>

          <div class="section-body">

            <h2 class="section-title">{{ $heading }}</h2> &nbsp;

            <a href="{{ route('albums.create') }}" class="btn btn-info mb-5 mt-2">
                Ajouter un album
            </a>

            <div class="row">

              @forelse ($albums as $album)

              <div class="col-12 col-md-4 col-lg-4">
                <article class="article article-style-c">
                  <div class="article-header">
                    <div class="article-image">
                      <a href="{{ route('albums.show', [$album->slug]) }}">
                        @if ($album->photos && count($album->photos) > 0)
                          <img width="350" height="233" src="{{ $album->photos[0]->thumbnail_url }}" alt="{{ $album->title }}">
                        @else
                            Pas de photo
                        @endif

                      </a>
                    </div>
                  </div>
                  <div class="article-details">
                    <div class="article-category"> <div class="bullet"></div> 
                        Mis à jour  {{ $album->updated_at->diffForHumans() }}
                    </div>
                    <div class="article-title">
                      <h2>
                        <a href="{{ route('albums.show', [$album->slug]) }}">
                          {{ $album->title }}
                        </a>
                      </h2>
                    </div>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. </p>
                    <div class="article-user">
                      
                      <div class="article-user-details">

                        <div class="text-job">
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </article>
              </div>

              @empty
                {{-- Pas de photo --}}

              @endforelse

            </div>
          </div>
        </section>

        <nav>
          {!! $albums->links() !!}
        </nav>

      </div>

@stop