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
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-left">Data KPI</h5>

                                
                                <button data-toggle="modal" data-target="#plusKategori" type="button"
                                    class="float-right btn btn-info btn-sm mr-2"><i class="fa fa-plus"></i>
                                    Kategori</button>
                            </div>
                            <div class="card-header">
                                <h5>Setting Orang : <a href="#" data-target="#setting" data-toggle="modal">
                                        {{ $settingOrang }}</a></h5>

                                <h5>Persen Pembagian : <a href="#" data-target="#setting" data-toggle="modal">
                                        {{ number_format($persen, 2) }} %</a></h5>
                            </div>
                            <div class="card-body">
                                @include('flash.flash')



                                <table class="table" id="table">
                                    <thead>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($kategori_kpi as $d)
                                            <tr>
                                                <td>{{ $d->urutan }}</td>
                                                <td><a class="sub-kategori" style="cursor: pointer"
                                                        id_kategori="{{ $d->id_kategori_kpi }}">{{ $d->nm_kategori }}</a>
                                                </td>
                                                <td align="center">
                                                    <a href="#" data-target="#editKategori" data-toggle="modal"
                                                        class="btn btn-xs btn-warning"><i class="fas fa-pen"></i></a>
                                                    <a href="{{ route('hapusKategoriKpi', [1, $d->id_kategori_kpi]) }}"
                                                        onclick="return confirm('Yakin dihapus ?')"
                                                        class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="col-lg-6">
                                <table class="table" id="table10">
                                    <thead>
                                        <th width="5%">#</th>
                                        <th>Nama Kategori</th>
                                        <th>Nama Sub Kategori</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($sub_kategori_kpi as $d)
                                            <tr>
                                                <td>{{ $d->urutan }}</td>
                                                <td>{{ $d->nm_kategori }}</td>
                                                <td>{{ $d->nm_sub_kategori }}</td>
                                                <td align="center" width="17%">
                                                    <a href="#"
                                                        data-target="#editSubKategori{{ $d->id_sub_kategori_kpi }}"
                                                        data-toggle="modal" class="btn btn-xs btn-warning"><i
                                                            class="fas fa-pen"></i></a>
                                                    <a href="{{ route('hapusKategoriKpi', [2, $d->id_sub_kategori_kpi]) }}"
                                                        onclick="return confirm('Yakin dihapus ?')"
                                                        class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- sub kategori kpi --}}
    <form action="{{ route('saveSetKpi') }}" method="post">
        @csrf
        <div class="modal fade" role="dialog" id="setting" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Tambah Sub Kategori</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Setting Orang</label>
                                    <input value="{{ $settingOrang }}" type="text" name="orang" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Persen</label>
                                    <input value="{{ $persen }}" type="text" name="persen" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- kategori kpi --}}
    <form action="{{ route('saveKategoriKpi') }}" method="post">
        @csrf
        <div class="modal fade" role="dialog" id="plusKategori" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Tambah Kategori Kpi</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="">Urutan</label>
                                    <input oninput="this.value=this.defaultValue;" type="text" readonly
                                        value="{{ $urutanKategori }}" name="urutan" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label for="">Nama Kategori</label>
                                    <input type="text" name="nm_kategori" class="form-control">
                                </div>
                            </div>
                            {{-- <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="">Icon</label>
                                    <input type="text" name="icon" class="form-control">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- sub kategori kpi --}}
    

    {{-- edit kategori --}}
    <form action="{{ route('editKategoriKpi') }}" method="post">
        @csrf
        <div class="modal fade" role="dialog" id="editKategori" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Tambah Kategori Kpi</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @foreach ($kategori_kpi as $d)
                            <div class="row">
                                <input type="hidden" name="id[]" value="{{ $d->id_kategori_kpi }}">
                                <input type="hidden" name="jenis" value="1">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="">Urutan</label>
                                        <input type="text" value="{{ $d->urutan }}" name="urutan[]"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label for="">Nama Kategori</label>
                                        <input type="text" value="{{ $d->nm_kategori }}" name="nm_kategori[]"
                                            class="form-control">
                                    </div>
                                </div>
                                {{-- <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="">Icon</label>
                                        <input type="text" value="{{ $d->icon }}" name="icon"
                                            class="form-control">
                                    </div>
                                </div> --}}
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- edit sub kategori --}}
    @foreach ($sub_kategori_kpi as $d)
        <form action="{{ route('editKategoriKpi') }}" method="post">
            @csrf
            <div class="modal fade" role="dialog" id="editSubKategori{{ $d->id_sub_kategori_kpi }}"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content ">
                        <div class="modal-header btn-costume">
                            <h5 class="modal-title text-light" id="exampleModalLabel">Tambah Sub Kategori</h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <input type="hidden" name="id" value="{{ $d->id_sub_kategori_kpi }}">
                                        <input type="hidden" name="jenis" value="2">
                                        <label for="">Urutan</label>
                                        <input type="text" value="{{ $d->urutan }}" name="urutan"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Nama Kategori</label>
                                        <select name="kategori_id" class="form-control select" id="">
                                            <option value="">- Pilih Kategori -</option>
                                            @foreach ($kategori_kpi as $i)
                                                <option {{ $d->id_kategori_kpi == $i->id_kategori_kpi ? 'selected' : '' }}
                                                    value="{{ $i->id_kategori_kpi }}">{{ $i->nm_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Nama Sub Kategori</label>
                                        <input type="text" value="{{ $d->nm_sub_kategori }}" name="nm_kategori"
                                            class="form-control">
                                    </div>
                                </div>
                                {{-- <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="">Icon</label>
                                        <input type="text" name="icon" value="{{ $d->icon }}"
                                            class="form-control">
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endforeach

    {{-- load ajax sub kategori --}}
    <form id="save-sub-kategori">
        @csrf
        <div class="modal fade" role="dialog" id="detail-kategori" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Sub Kategori Kpi</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="load-sub-kategori"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="save-tambah-sub-kategori">
        @csrf
        <div class="modal fade" role="dialog" id="plusSubKategori" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Tambah Sub Kategori</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="">Urutan</label>
                                    <input type="hidden" id="idKategori" name="kategori_id">
                                    <input type="text" readonly value="{{ $urutanSubKategori }}" name="urutan"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Nama Sub Kategori</label>
                                    <input type="text" name="nm_kategori" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Rupiah</label>
                                    <input type="text" name="rupiah" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            function subKategori(id_kategori) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('sub_kategori') }}?id_kategori=" + id_kategori,
                    success: function(response) {
                        
                        $("#load-sub-kategori").html(response);
                        $("#detail-kategori").modal('show')
                    }
                });
            }

            $(document).on('click', '.sub-kategori', function(e) {
                e.preventDefault()
                id_kategori = $(this).attr('id_kategori')
                subKategori(id_kategori)
            })

            $(document).on('click', '.btnTambahSub', function(){
                $("#idKategori").val($(this).attr('id_kategori'))
            })

            $(document).on('submit', '#save-tambah-sub-kategori', function(e){
                e.preventDefault()
                var datas = $("#save-tambah-sub-kategori").serialize()
                id_kategori = $("#idKategori").val()
                
                $.ajax({
                    type: "GET",
                    url: "{{route('save_tambah_sub_kategori')}}",
                    data: datas,
                    success: function (r) {
                        subKategori(id_kategori)
                        $("#plusSubKategori").modal('hide')
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'success',
                            title: 'Berhasil edit sub kategori'
                        });
                    }
                });

            })
            
            $(document).on('submit', '#save-sub-kategori', function(e){
                e.preventDefault()
                var datas = $("#save-sub-kategori").serialize()
                id_kategori = $("#id-kategori").val()
                
                $.ajax({
                    type: "GET",
                    url: "{{route('save_sub_kategori')}}",
                    data: datas,
                    success: function (r) {
                        subKategori(id_kategori)
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'success',
                            title: 'Berhasil edit sub kategori'
                        });
                    }
                });

            })

            $(document).on('click', '.delete-sub-kategori', function(e){
                e.preventDefault()
                id_sub_kategori = $(this).attr('id_sub_kategori')
                id_kategori = $(this).attr('id_kategori')
                if(confirm('Yakin ingin dihapus ?')) {
                    $.ajax({
                        type: "GET",
                        url: "{{route('delete_subkategori')}}?id_sub_kategori="+id_sub_kategori,
                        success: function (r) {
                            subKategori(id_kategori)
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: 'Berhasil edit sub kategori'
                            });
                        }
                    });
                }

            })
        });
    </script>
@endsection
