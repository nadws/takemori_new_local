<div class="row sub-kategori float-center justify-content-center">
    <div class="col-lg-12 col-12">
        <h5 class="text-center">Sub Kategori</h5>
    </div>
    @foreach ($sub_kategori as $no => $i)
        <div class="col-5 col-lg-4 text-center awak-sub-card custom-control custom-checkbox">
            <div nmSubKategori="{{ $i->nm_sub_kategori }}" id_sub_kategori="{{ $i->id_sub_kategori_kpi }}" rupiah="{{ $i->rupiah }}" class="card shadow card-hover sub-card sub-card-{{ $no + 1 }}" no="{{ $no + 1 }}"
                value="{{ $i->id_sub_kategori_kpi }}" style="border-radius: 20px;">
                <div class="card-body">
                    <input type="checkbox" name="sub_kategori_id" value="{{ $i->id_sub_kategori_kpi }}"
                        class="custom-control-input" id="subCheck{{ $no + 1 }}">
                    <h5>{{ $i->nm_sub_kategori }}</h5>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="row sub-kategori">
    <div class="col-lg-8">
        <label for="">Ket</label>
        <input type="text" class="form-control" name="ket">
        <input type="hidden" class="form-control" id="nmSubKategori" name="nmSubKategori">
    </div>
    <div class="col-lg-4">
        <label for="">Rupiah</label>
        <input type="text" id="rupiah" required class="form-control" name="rupiah">
    </div>
</div>
<div class="row mt-5 sub-kategori float-center justify-content-center">
    <div class="col-lg-12 col-12 mb-3">
        <h5 class="text-center">
            Karyawan
            <div class="custom-control custom-checkbox text-right btnCheckAll">
                <input type="checkbox" id="checkAll">
                <label for="checkAll">Check All</label>
            </div>
        </h5>
    </div>

    @foreach ($karyawan as $i => $d)
        <div class="col-4 col-lg-4 text-center custom-control custom-checkbox">
            {{-- <button type="button" class="card-hover btn border karyawan-card karyawan-card-{{$i+1}}" no="{{$i+1}}" style="border-radius: 20px;">{{ $d->nama }}</button> --}}

            <div for="karyawanCheck{{ $i + 1 }}"
                class=" card shadow p-3 card-hover karyawan-card karyawan-card-{{ $i + 1 }}"
                no="{{ $i + 1 }}" style="border-radius: 20px; ">
                <input type="checkbox" name="id_karyawan[]" value="{{ $d->id_karyawan }}"
                    class="custom-control-input checkAll" id="karyawanCheck{{ $i + 1 }}">
                {{-- <label class="custom-control-label " for="karyawanCheck{{$i+1}}">{{ $d->nama }}</label> --}}
                <p for="check{{ $i + 1 }}" class="m-auto">{{ $d->nama }}</p>

            </div>
        </div>
    @endforeach
</div>
