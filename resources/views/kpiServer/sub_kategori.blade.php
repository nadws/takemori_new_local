<input type="hidden" id="id-kategori" value="{{ $idKategori }}">
<button data-toggle="modal" data-target="#plusSubKategori" id_kategori="{{ $idKategori }}" type="button" class="btnTambahSub mb-3 float-right btn btn-info btn-sm mr-2"><i
        class="fa fa-plus"></i> Sub
    Kategori</button>
<table class="table">
    <thead>
        <tr>
            <th width="15%">#</th>
            <th>Nama Sub Kategori</th>
            <th width="25%">Rupiah</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($subKategori as $d)
            <tr>
                <td>
                    <input type="text" class="form-control" name="urutan[]" value="{{ $d->urutan }}">
                    <input type="hidden" class="form-control" name="id_sub_kategori[]"
                        value="{{ $d->id_sub_kategori_kpi }}">
                </td>
                <td>
                    <input type="text" class="form-control" name="nm_sub_kategori[]"
                        value="{{ $d->nm_sub_kategori }}">
                </td>
                <td>
                    <input type="text" class="form-control" name="rupiah[]"
                        value="{{ $d->rupiah }}">
                </td>
                <td align="center">
                    {{-- <a href="#" 
                        class="btn btn-xs btn-warning edit-sub-kategori"><i class="fas fa-pen"></i></a> --}}
                    <a href="#" id_sub_kategori="{{ $d->id_sub_kategori_kpi }}" id_kategori="{{ $idKategori }}"
                        class="btn btn-xs btn-danger delete-sub-kategori"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
