@extends('template.master')
@section('content')
<div class="content-wrapper">
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

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @include('flash.flash')
                    <div class="card">
                        <div class="card-header">
                            <h5>Data Produk</h5>
                        </div>

                        <div class="card-body">
                            <table class="table  " id="table">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>SKU</th>
                                        <th>Kategori</th>
                                        <th>QTy</th>
                                        <th>Satuan</th>
                                        <th>Harga Jual</th>
                                        <th>Komisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=1;
                                    @endphp
                                    @foreach ($produk as $p)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$p->nm_produk}}</td>
                                        <td>{{$p->sku}}</td>
                                        <td>{{$p->nm_kategori}}</td>
                                        <td>{{$p->debit - ($p->kredit + $p->kredit_penjualan)}}</td>
                                        <td>{{$p->satuan}}</td>
                                        <td>{{number_format($p->harga,0)}}</td>
                                        <td>{{$p->komisi}} %</td>
                                        
                                    </tr>
                                    @endforeach


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
<!-- /.content-wrapper -->
@endsection

