@extends('layouts.main')

@section('content')

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>
              {{ $heading }}
            </h1>
          </div>

          {{-- Liens de triage --}}
          @includeIf('includes.sort')

          <div class="section-body mt-5">

            <h2 class="section-title">{{ $heading }}</h2>
            <div class="row">

              @forelse ($photos as $photo)

              <div class="col-12 col-md-4 col-lg-4">
                <article class="article article-style-c">
                  <div class="article-header">
                    <div class="article-image">
                      <a href="{{ route('photos.show', [$photo->slug]) }}">
                        <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}"
                        width="350" height="233"
                      >
                      </a>
                    </div>
                  </div>
                  <div class="article-details">
                    <div class="article-category"> <div class="bullet"></div> 
                      <a href="#">Posté {{ $photo->created_at->diffForHumans() }}</a></div>
                    <div class="article-title">
                      <h2>
                        <a href="{{ route('photos.show', [$photo->slug]) }}">
                          {{ $photo->title }}
                        </a>
                      </h2>
                    </div>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. </p>
                    <div class="article-user">
                      <img alt="{{ $photo->album->user->name }}" avatar src="../assets/img/avatar/avatar-1.png">
                      <div class="article-user-details">
                        <div class="user-detail-name">
                          <a href="#">
                            {{ $photo->album->user->name }}
                          </a>
                          {{ $photo->album->user->photos->count() }} {{ Str::plural('photo', $photo->album->user->photos->count()) }}
                        </div>
                        <div class="text-job">
                          <a href="">
                            {{ $photo->album->title }}
                          </a>
                          {{ $photo->album->photos->count() }} 
                          {{ Str::plural('photo', $photo->album->photos->count()) }}
                        </div>

                        @if(Auth::check() && Auth::id() === $photo->album->user_id)
                          <div class="destroy text-right">
                              <form action="{{ route('photos.destroy', [$photo->slug]) }}" method="post" class="destroy">
                                  @csrf
                                  @method('DELETE')
                                  <button class="btn btn-danger" type="submit">
                                      <i class="far fa-trash-alt" style="color: #fff; font-size: 1.5rem;"></i>
                                  </button>
                              </form>
                          </div>
                        @endif

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
          {!! $photos->links() !!}
        </nav>

      </div>

@stop