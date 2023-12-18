@php
    $setMenit = DB::table('tb_menit')
        ->where('id_lokasi', $lokasi)
        ->first();
@endphp
<?php foreach ($menu2 as $m) : if($m->nm_menu == '') {continue;} ?>
<tr>
    <td></td>
    <td style="text-transform: lowercase;">
        <?= $m->nm_menu ?> <span class="text-danger">({{ $m->ttlMenuSemua }})</span>
    </td>
    <td>
        <?= $m->request ?>
    </td>
    <td>
        <?= $m->qty ?>
    </td>
    <td><a kode="<?= $m->id_order ?>" class="btn btn-warning text-light btn-sm cancel" id_meja="{{ $m->id_meja }}"><i
                class="fas fa-times"></i></a></td>
    <?php foreach ($tb_koki as $k) : ?>
    <?php if($k->id_karyawan == $m->id_koki1 || $k->id_karyawan == $m->id_koki2 || $k->id_karyawan == $m->id_koki3): ?>
    <td><i class="text-success fas fa-check-circle"></i></td>
    <?php else: ?>
    <td></td>
    <?php endif; ?>
    <?php endforeach ?>
    @php
        $mulai = new DateTime($m->j_mulai);
        $selesai = new DateTime($m->j_selesai);
        $menit = $selesai->diff($mulai);
    @endphp
    <?php if (date('H:i', strtotime($m->j_selesai)) < date('H:i', strtotime($m->j_mulai . '+'.$setMenit->menit.'minutes'))) : ?>
    <td><b style="color:blue;">
            <?= date('H:i', strtotime($m->j_selesai)) ?> / {{ $menit->i }} Menit /
            {{ $menit->s }} Detik
        </b></td>
    <?php else : ?>
    <td><b style="color:red;">
            <?= date('H:i', strtotime($m->j_selesai)) ?> / {{ $menit->i }} Menit /
            {{ $menit->s }} Detik
        </b></td>
    <?php endif ?>
</tr>
<?php endforeach ?>
