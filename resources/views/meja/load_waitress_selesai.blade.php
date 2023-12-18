@foreach ($menu2 as $m)
    @php
        if ($m->nm_menu == '') {
            continue;
        }
    @endphp
    <tr>
        <td></td>
        <td style="text-transform: lowercase;">{{ $m->nm_menu }}</td>
        <td style="text-transform: lowercase;">{{ $m->request }}</td>
        <td>{{ $m->qty }}</td>
        <td>Selesai</td>
        <?php foreach ($waitress as $k) : ?>
        <?php if ($k->nama == $m->pengantar) : ?>
        <td><i class="text-success fas fa-check-circle"></i></td>
        <?php else : ?>
        <td></td>
        <?php endif; ?>
        <?php endforeach ?>

        <td>
            <?php if ($m->selisih > '40') : ?>
            <b style="color:red;">
                <?= number_format($m->selisih, 0) ?> Menit
            </b>
            <?php else : ?>
            <b style="color:blue;">
                <?= number_format($m->selisih, 0) ?> Menit
            </b>
            <?php endif ?>

            <?php if ($m->wait_2 > '40') : ?>
            <b style="color:red;">
                <?= number_format($m->wait_2, 0) ?> Menit
            </b>
            <?php else : ?>
            <b style="color:blue;">
                <?= number_format($m->wait_2, 0) ?> Menit
            </b>
            <?php endif ?>
        </td>
        <td>{{ date('H:i', strtotime($m->created_at)) }} </td>
    </tr>
@endforeach
@foreach ($majo_hide as $m)
    <tr>
        <td></td>
        <td style="text-transform: lowercase;">{{ $m->nm_produk }}</td>
        <td></td>
        <td>{{ $m->jumlah }}</td>
        <td>Selesai</td>
        <?php foreach ($waitress as $k) : ?>
        <?php if ($k->nama == $m->pengantar) : ?>
        <td><i class="text-success fas fa-check-circle"></i></td>
        <?php else : ?>
        <td></td>
        <?php endif; ?>
        <?php endforeach ?>

        <td>

        </td>
        <td></td>
    </tr>
@endforeach
