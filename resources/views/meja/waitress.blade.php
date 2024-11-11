<table class="table" width="100%">
    <thead>
        <tr class="header">
            <th>Meja</th>
            <th>Menu</th>
            <th>Request</th>
            <th>Qty</th>
            <th>Status</th>

            {{-- <th>Masak / Antar </th> --}}
            <th>Jam Order </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($meja as $m)
            @php
                $order = DB::table('tb_order2')
                    ->where('no_order', $m->no_order)
                    ->groupBy('no_order2')
                    ->get();
            @endphp

            <tr class="header">
                <td class="bg-info" width="8%" style="white-space: nowrap;">
                    <?= $m->nm_meja ?>
                </td>
                <td class="bg-info" style="vertical-align: middle;">
                    <a class="muncul muncul{{ $m->id_meja }}  btn btn-primary btn-sm" id_meja="{{ $m->id_meja }}"
                        no_order="{{ $m->no_order }}">View</a>
                    <a class="hilang hilang{{ $m->id_meja }}  btn btn-primary btn-sm" id_meja="{{ $m->id_meja }}"
                        style="display:none">View</a>
                    <div class="dropdown d-inline-block mb-2">
                        <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus"></i> Pesanan
                        </button>
                        <div class="dropdown-menu" aria-labelledby="invoice">
                            <a data-toggle="modal" class="btn_tbh dropdown-item" no_order="<?= $m->no_order ?>"
                                href="#tbh_menu">Resto</a>
                            <a data-toggle="modal" class="btn_tbh_majo dropdown-item" no_order="<?= $m->no_order ?>"
                                href="#tbh_menu_majo">Stk</a>
                        </div>
                    </div>
                    <a target="_blank" href="{{ route('billing', ['no' => $m->no_order]) }}"
                        class="btn btn-success btn-sm mb-2"><i class="fas fa-print"></i> Bill</a>
                    <?php if ($m->prn == 'T') : ?>
                    <a href="{{ route('checker', ['no' => $m->no_order]) }}" class="btn btn-success btn-sm mb-2"><i
                            class="fas fa-print"></i> Checker</a>
                    <?php else : ?>
                    <a href="{{ route('copy_checker', ['no' => $m->no_order]) }}" class="btn btn-success btn-sm mb-2"><i
                            class="fas fa-print"></i> Copy Checker</a>
                    <?php endif ?>
                    <?php if ($m->t_prn == 'T') : ?>
                    <a href="{{ route('checker_tamu', ['no' => $m->no_order]) }}" class="btn btn-success btn-sm mb-2"><i
                            class="fas fa-print"></i> Checker tamu</a>
                    <?php else : ?>
                    <a href="{{ route('copy_checker_tamu', ['no' => $m->no_order]) }}"
                        class="btn btn-success btn-sm mb-2"><i class="fas fa-print"></i> Copy Checker tamu</a>
                    <?php endif ?>
                    <a href="{{ route('all_checker', ['no' => $m->no_order]) }}" class="btn btn-success btn-sm mb-2"><i
                            class="fas fa-print"></i> Print all</a>

                    <?php if (($m->qty1 -  $m->qty2) != '0') : ?>
                    <?php if ($m->selesai != 'selesai') : ?>
                    <?php else : ?>
                    <?php $l = 1;
                foreach ($order as $a) : ?>
                    <div class="dropdown d-inline-block mb-2">
                        <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                            id="invoice{{ $a->no_order2 }}" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fas fa-file-invoice"></i> invoice {{ $l++ }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="invoice{{ $a->no_order2 }}">

                            <a target="_blank" class="dropdown-item"
                                href="{{ route('print_nota', ['no' => $a->no_order2]) }}">Print</a>
                            <a data-toggle="modal" href="#edit_pembayaran" no_order="{{ $a->no_order2 }}  "
                                class="dropdown-item btn_edit_pembayaran">Edit</a>
                        </div>
                    </div>
                    <?php endforeach ?>
                    <a href="javascript:void(0)" class="btn btn-success btn-sm btn_pembayaran mb-2"
                        no_order="{{ $m->no_order }}"><i class="fas fa-funnel-dollar"></i> Pembayaran</a>

                    <?php endif ?>

                    <?php else : ?>

                    <?php $i = 1;
                foreach ($order as $a) : ?>

                    <div class="dropdown d-inline-block mb-2">
                        <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                            id="invoice{{ $a->no_order2 }}" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fas fa-file-invoice"></i> invoice {{ $i++ }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="invoice{{ $a->no_order2 }}">
                            <a target="_blank" class="dropdown-item"
                                href="{{ route('print_nota', ['no' => $a->no_order2]) }}">Print</a>
                            <a data-toggle="modal" href="#edit_pembayaran" no_order="{{ $a->no_order2 }}"
                                class="dropdown-item btn_edit_pembayaran">Edit</a>
                        </div>
                    </div>
                    <?php endforeach ?>
                    <a class="btn btn-success btn-sm clear" kode="{{ $m->no_order }}"><i
                            class="fas fa-hand-sparkles"></i>
                        Clear up</a>
                    <?php endif ?>
                </td>
                <td class="bg-info"></td>
                <td class="bg-info"></td>
                <td class="bg-info"></td>

                {{-- disini --}}
                <td colspan="50" class="bg-info">
                    <?= number_format($m->total_jam_pesan, 0) ?> Menit
                </td>
            </tr>
            @php
                $menu = DB::table('menu1')
                    ->where('id_lokasi', $loc)
                    ->where('id_meja', $m->id_meja)
                    ->get();

                $majo = DB::select("SELECT a.*, c.nm_produk
                        FROM tb_pembelian AS a
                        LEFT JOIN tb_produk AS c ON c.id_produk = a.id_produk
                        WHERE a.no_nota = '$m->no_order' AND a.lokasi = '$loc' and a.selesai = 'diantar'
                        GROUP BY a.id_pembelian");

            @endphp
            <tr>
    <tbody class="load_menu_s{{ $m->id_meja }}"></tbody>
    </tr>
    @foreach ($menu as $m)
        @if ($m->nm_menu != '')
            <tr class="header">
                <td></td>
                <td style="white-space:nowrap;text-transform: lowercase;">{{ $m->nm_menu }}</td>
                <td style="white-space:nowrap;text-transform: lowercase;">{{ $m->request }}</td>
                <td> {{ $m->qty }}</td>
                <td>
                    {{ $m->selesai }}
                </td>
                <td>{{ date('H:i', strtotime($m->created_at)) }} </td>
            </tr>
        @else
        @endif
    @endforeach
    <?php foreach ($majo as $m) :
                if ($m->nm_produk == '') {
                continue;
                } else {

                }
            ?>

    <tr class="header">
        <td></td>
        <td style="white-space:nowrap;text-transform: lowercase;">{{ $m->nm_produk }}</td>
        <td></td>
        <td> {{ $m->jumlah }}</td>
        <?php if (!empty($m->pengantar)) : ?>
        <td><a kode="{{ $m->id_pembelian }}" class="btn btn-info btn-sm selesai_majo"><i
                    class="fas fa-thumbs-up"></i></a>
        </td>
        <?php else : ?>
        <td><a kode="{{ $m->id_pembelian }}" class="btn btn-info btn-sm gagal"><i class="fas fa-thumbs-up"></i></a>
        </td>
        <?php endif ?>
        <?php foreach ($waitress as $k) : ?>
        <?php if (!empty($m->pengantar)) : ?>
        <?php if ($m->pengantar == $k->nama) : ?>
        <td><a kode="{{ $m->id_pembelian }}" class="btn btn-warning btn-sm un_waitress_majo"><i
                    class="fas fa-user-check"></i></a></td>
        <?php else : ?>
        <td></td>
        <?php endif ?>

        <?php else : ?>
        <td><a kode="{{ $m->id_pembelian }}" kry="{{ $k->nama }}"
                class="btn btn-sm btn-success waitress_majo"><i class="fas fa-check"></i></a></td>
        <?php endif ?>
        <?php endforeach ?>
        <td></td>
        <td>{{ date('H:i', strtotime($m->tgl_input)) }}</td>
    </tr>

    <?php endforeach ?>
    @endforeach

    </tbody>
</table>
