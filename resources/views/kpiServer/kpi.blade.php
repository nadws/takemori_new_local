@extends('template.master')
@section('content')

    <div class="content-wrapper" style="min-height: 511px;">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2 justify-content-center">
                    <div class="col-sm-12">
                    </div>

                </div>
            </div>
        </div>

        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-left">Kpi Server
                                    {{ date('d-m-Y', strtotime($tgl1)) . ' ~ ' . date('d-m-Y', strtotime($tgl2)) }}</h5>
                                <button data-toggle="modal" data-target="#view" type="button"
                                    class="float-right btn btn-info btn-sm"><i class="fa fa-calendar-alt"></i>
                                    View</button>
                                <button data-toggle="modal" data-target="#tambah" type="button"
                                    class="float-right btn btn-info btn-sm mr-2"><i class="fa fa-plus"></i> Tambah</button>

                            </div>
                            <div class="card-header" style="font-size: 18px">
                                <p>Setting Orang / persen : {{ $settingOrang }} / {{ number_format($persenBagi, 2) }} %</p>
                                <p>Service Charge Takemori ~ Soondobu : {{ number_format($kom) }} ~
                                    {{ number_format($kom2) }}</p>
                                @php
                                    $ttlRp = $kom * $persenBagi + $kom2 * $persenBagi;
                                    $pointR = $ttlRp / $settingOrang;
                                @endphp
                                {{-- <h5>1 Point / Total Rp : {{ number_format($rupiah, 0) }} / {{ number_format($kom * $persenBagi,0)}}</h5> --}}
                                <p>10 Point / Total Rp : {{ number_format($pointR, 0) }} / {{ number_format($ttlRp, 0) }}
                                </p>
                            </div>
                            <div class="card-body">
                                @include('flash.flash')
                                <table class="table table-md" width="100%" id="table">
                                    <thead>
                                        <th width="5%">#</th>
                                        <th width="55%">Nama</th>
                                        <th width="15%">Point</th>
                                        <th class="text-center">Rupiah</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($karyawan as $no => $d)
                                            @php
                                                $ttlPointRp = $pointR / 10;
                                            @endphp
                                            <tr>
                                                <td>{{ $no + 1 }}</td>
                                                <td>{{ $d->nama }}</td>
                                                <td>{{ 10 - $d->ttl }}</td>
                                                <td style="text-align: right">
                                                    {{ number_format($pointR - $ttlPointRp * $d->ttl, 0) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .card-hover:hover {
                background-color: #ffd43b;
                cursor: pointer;
            }

            .card-active {
                background-color: #ffd43b;
                cursor: pointer;
            }

            .text-p {
                font-size: 10px;
            }
        </style>
        <form action="" method="get">
            <div class="modal fade" role="dialog" id="view" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content ">
                        <div class="modal-header btn-costume">
                            <h5 class="modal-title text-light" id="exampleModalLabel">View</h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="">Dari</label>
                                    <input class="form-control" type="date" name="tgl1">
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

        <form action="{{ route('saveDendaKpi') }}" method="post">
            @csrf
            <input type="hidden" name="tgl1" value="{{ $tgl1 }}">
            <input type="hidden" name="tgl2" value="{{ $tgl2 }}">
            <div class="modal fade" role="dialog" id="tambah" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content ">
                        <div class="modal-header btn-costume">
                            <h5 class="modal-title text-light" id="exampleModalLabel">Tambah Kpi</h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <button type="button" class="btn btn-sm btn-info mb-3" id="btnKembali"><i
                                    class="fas fa-arrow-left"></i> Kembali</button>
                            <div id="loadSubKategori"></div>

                            <div class="row float-center justify-content-center">

                                @foreach ($kategori_kpi as $d => $i)
                                    <div class="col-5 col-lg-4 text-center awak-card">
                                        <div class="card shadow card-kpi card-hover" value="{{ $i->id_kategori_kpi }}"
                                            style="border-radius: 20px;">
                                            <div class="card-body">
                                                {{-- <i class="fas {{ $i->icon }} fa-2x mb-3"></i><br> --}}
                                                <h5>{{ $i->nm_kategori }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $('#btnKembali').hide();
            $('.sub-kategori').hide();
            $(document).on('click', '#btnKembali', function() {
                $('.awak-card').show();
                $('#btnKembali').hide();
                $('.sub-kategori').hide();
                $('.select').val([]).trigger('change');
            })

            $(document).on('click', '.card-kpi', function() {
                var val = $(this).attr('value')
                $.ajax({
                    type: "GET",
                    url: "{{ route('subKategori') }}?kategori_id=" + val,
                    success: function(r) {
                        $("#loadSubKategori").html(r);
                        $('.select').select2()
                    }
                });
                $('.awak-card').hide();
                $('#btnKembali').show();
                $('.sub-kategori').show();
            })

            $(document).on('click', '.sub-card', function() {
                $("#rupiah").val($(this).attr('rupiah'))
                $("#nmSubKategori").val($(this).attr('nmSubKategori'))

                $(".sub-card").removeClass('card-active');
                $("#subCheck" + no).removeAttr("checked");

                var no = $(this).attr('no')
                var val = $(this).attr('value')

                $(".sub-card-" + no).addClass('card-active');
                $("#subCheck" + no).attr("checked", true);
                $("#sub-kategori-id").val(val)
            })

            $(document).on('click', '.karyawan-card', function() {
                var no = $(this).attr('no')
                var val = $(this).attr('value')
                if ($(this).hasClass('card-active')) {
                    $(".karyawan-card-" + no).removeClass('card-active');
                    $("#karyawanCheck" + no).removeAttr("checked");

                } else {
                    $("#karyawanCheck" + no).attr("checked", true);
                    $(".karyawan-card-" + no).addClass('card-active');
                }

            })

            $(document).on('click', '#checkAll', function(e) {


                if ($('.karyawan-card').hasClass('card-active')) {
                    $(".karyawan-card").removeClass('card-active');
                    $(".checkAll").removeAttr("checked");
                } else {
                    $(".checkAll").attr("checked", true);
                    $(".karyawan-card").addClass('card-active');
                }
            })

        });
    </script>
@endsection
