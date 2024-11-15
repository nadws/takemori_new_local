@extends('template.master')
@section('content')
    <?php $l = 1; 
$point = 0;
$point2 = 0;
$orang = 0;
foreach ($masak as $k) : ?>
    <?php $orang = $l++; ?>
    <?php $point += $k->point_berhasil + $k->point_gagal; ?>



    <?php  endforeach ?>
    <?php $orang2 = !$orang ? '0' : $orang; ?>
    <div class="content-wrapper" style="min-height: 511px;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2 justify-content-center">
                    <div class="col-sm-12">
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <?php $service_charge = $service->total * 0.07; ?>
        <?php $kom = ((($service_charge / 7) * $persen->jumlah_persen) / $jumlah_orang->jumlah) * $orang2; ?>
        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 style="font-weight: bold">
                                    Point masak
                                    <?= date('d-m-Y', strtotime($tgl1)) ?> ~
                                    <?= date('d-m-Y', strtotime($tgl2)) ?>
                                </h5>

                                <h5>Org p/r :
                                    <?= number_format($jumlah_orang->jumlah, 0) ?> /
                                    <?= number_format($orang, 0) ?>
                                </h5>
                                <h5>
                                    Service charge mau dibagi :
                                    <?= number_format(($service_charge / 7) * $persen->jumlah_persen, 0) ?>
                                </h5>
                                @if ($jumlah_orang->jumlah < $orang)
                                @else
                                    <h5>Service charge real :
                                        <?= number_format($kom, 0) ?>
                                    </h5>
                                @endif

                            </div>
                            <div class="card-header">
                                <ul class="nav nav-tabs mb-2" id="custom-tabs-two-tab" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link <?= $id_lokasi == 1 ? 'active btn-info' : '' ?>"
                                            href="<?= route('point_kitchen') ?>?id_lokasi=1">Takemori</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= $id_lokasi == 2 ? 'active btn-info' : '' ?>"
                                            href="<?= route('point_kitchen') ?>?id_lokasi=2">Soondobu</a>
                                    </li>

                                </ul>
                                <a href="{{ route('point_export_server') }}?id_lokasi={{ $id_lokasi }}&tgl1={{ $tgl1 }}&tgl2={{ $tgl2 }}"
                                    class="btn btn-info float-right btn-sm "><i class="fas fa-file-excel"></i>
                                    Export</a>
                                <a href="" data-target="#view" data-toggle="modal"
                                    class="btn btn-info float-right btn-sm mr-2"><i class="fas fa-eye"></i> View</a>
                            </div>
                            <div class="card-body">

                                <table width="100%" class="table " id="table" style="font-size: 11px">
                                    <thead style="white-space: nowrap; ">
                                        <tr>
                                            <th>#</th>
                                            <th style="font-size: 10px;text-align: center">Nama</th>
                                            <th style="font-size: 10px;text-align: right">M</th>
                                            <th style="font-size: 10px;text-align: right">Gaji</th>
                                            <th style="font-size: 10px;text-align: right">Point <br> Masak </th>
                                            <th style="font-size: 10px;text-align: right">Non Point <br> Masak</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $i = 1; foreach ($masak as $k) : ?>
                                        <tr>
                                            <td>
                                                <?= $i++ ?>
                                            </td>
                                            <td>
                                                <?= $k->nama ?>
                                            </td>
                                            <td style="text-align: right">
                                                <?= number_format($k->rp_m, 0) ?>
                                            </td>
                                            <?php $gaji = $k->rp_m * $k->qty_m + $k->rp_e * $k->qty_e + $k->rp_sp * $k->qty_sp; ?>
                                            <td style="text-align: right">
                                                <?= number_format($gaji, 0) ?>
                                            </td>
                                            <?php $kom1 = $point == '' ? 0 : ($k->point_berhasil / $point) * $kom; ?>

                                            <td style="text-align: right">
                                                <?= number_format($k->point_berhasil, 1) ?>
                                                <?= $jumlah_orang->jumlah < $orang ? '' : '/' . number_format($kom1, 1) ?>
                                            </td>
                                            <?php $kom3 = ($k->point_gagal / $point) * $kom; ?>

                                            <td style="text-align: right">
                                                <?= number_format($k->point_gagal, 0) ?>
                                                <?= $jumlah_orang->jumlah < $orang ? '' : '/' . number_format($kom3, 0) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>

    <form action="" method="get">
        <div class="modal fade" role="dialog" id="view" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">View Point</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">Dari</label>
                                <input class="form-control" type="date" name="tgl1">
                                <input class="form-control" type="hidden" name="id_lokasi" value="{{ $id_lokasi }}">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Sampai</label>
                                <input class="form-control" type="date" name="tgl2">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
@endsection
