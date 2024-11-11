<ul class="nav nav-pills mb-3 custom-scrollbar-css" id="pills-tab" role="tablist">
    @foreach ($distribusi as $d)
    <li class="nav-item">
        @if (empty($d->jumlah))
            <a href="{{ route('head', ['id' => $d->id_distribusi]) }}"
                class="nav-link {{ $id == $d->id_distribusi ? 'active' : '' }}  badge-notif"><strong>{{ $d->nm_distribusi }}</strong></a>
        @else
            <a href="{{ route('head', ['id' => $d->id_distribusi]) }}" data-badge="{{ $d->jumlah }}"
                class="nav-link {{ $id == $d->id_distribusi ? 'active' : '' }} badge-notif"><strong>{{ $d->nm_distribusi }}</strong></a>
        @endif
    </li>
    @endforeach
   
    <input type="hidden" id="jumlah1" value="{{ $orderan[0]->jml_order }}">
</ul>

