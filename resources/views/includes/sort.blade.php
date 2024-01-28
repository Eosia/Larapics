<div class="float-right">
    <div class="dropdown d-inline mr-2">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Trier par
        </button>
        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);">

            @empty($search)
                <a class="dropdown-item" href="{{ url()->current() }}?sort=newest">Récents</a>
            @else
                <a class="dropdown-item" href="{{ url()->current() }}?search={{ $search }}&sort=newest">Récents</a>
            @endempty

            @empty($search)
                <a class="dropdown-item" href="{{ url()->current() }}?sort=oldest">Anciens</a>
            @else
                <a class="dropdown-item" href="{{ url()->current() }}?search={{ $search }}&sort=oldest">Anciens</a>
            @endempty

            @empty($search)
                <a class="dropdown-item" href="{{ url()->current() }}?sort=download">Téléchargées</a>
            @else
                <a class="dropdown-item" href="{{ url()->current() }}?search={{ $search }}&sort=download">Téléchargées</a>
            @endempty

            @empty($search)
                <a class="dropdown-item" href="{{ url()->current() }}?sort=popular">Populaires</a>
            @else
                <a class="dropdown-item" href="{{ url()->current() }}?search={{ $search }}&sort=popular">Populaires</a>
            @endempty

        </div>
    </div>
</div>