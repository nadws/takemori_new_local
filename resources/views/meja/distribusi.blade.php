<ul class="nav nav-pills mb-3 custom-scrollbar-css" id="pills-tab" role="tablist">
    <?php foreach ($distribusi as $d) : ?>
    <li class="nav-item">
        <?php if (empty($d->jumlah)) : ?>
            <a href="{{ route('meja', ['id' => $d->id_distribusi]) }}" class="nav-link {{$id == $d->id_distribusi ? 'active' : '' }} badge-notif"><strong>{{
                    $d->nm_distribusi }}</strong></a>
            <?php else : ?>
            <a href="{{ route('meja', ['id' => $d->id_distribusi]) }}" data-badge="{{ $d->jumlah }}"
                class="nav-link {{$id == $d->id_distribusi ? 'active' : '' }} badge-notif"><strong>{{ $d->nm_distribusi
                    }}</strong></a>
            <?php endif ?>
    </li>

    <?php 
    endforeach ?>
    {{-- @foreach ($orderan as $o) --}}
    <input type="hidden" id="jumlah1" value="{{ $orderan->jml_order ?? 0 }}">
    {{-- @endforeach --}}

</ul>