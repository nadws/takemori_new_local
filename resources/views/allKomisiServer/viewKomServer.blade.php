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
            <div class="">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <a href="" data-target="#view" data-toggle="modal"
                                    class="btn btn-info float-left btn-sm mr-2"><i class="fas fa-eye"></i> View</a>
                                <h4>Kom Server {{ date('d/m/Y', strtotime($tgl1)) }} ~ {{ date('d/m/Y', strtotime($tgl2)) }}
                                </h4>

                            </div>
                            <div class="card-header">

                                {{-- takemori --}}
                                <div id="loadTakemori"></div>
                                <center>
                                    <div class="spinner-border" id="spinnerTkm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </center>

                                {{-- soondobu --}}
                                <div id="loadSoondobu"></div>
                                <center>
                                    {{-- <div class="spinner-border" id="spinnerSdb" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div> --}}
                                </center>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

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
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $.ajax({
                type: "GET",
                url: "{{ route('loadTakemori') }}",
                data: {
                    tgl1: "{{ $tgl1 }}",
                    tgl2: "{{ $tgl2 }}",
                },
                beforeSend: function() {
                    $("#spinnerTkm").show();
                },
                success: function(r) {
                    $("#loadTakemori").html(r);
                    $("#spinnerTkm").hide();
                    $('#table').DataTable({

                        "bSort": true,
                        // "scrollX": true,
                        "paging": true,
                        "stateSave": true,
                        "scrollCollapse": true
                    });
                }
            });

            $.ajax({
                type: "GET",
                url: "{{ route('loadSoondobu') }}",
                data: {
                    tgl1: "{{ $tgl1 }}",
                    tgl2: "{{ $tgl2 }}",
                },
                beforeSend: function() {
                    $("#spinnerSdb").show();
                },
                success: function(r) {
                    $("#loadSoondobu").html(r);
                    $("#spinnerSdb").hide();
                    $('#table10').DataTable({

                        "bSort": true,
                        // "scrollX": true,
                        "paging": true,
                        "stateSave": true,
                        "scrollCollapse": true
                    });
                }
            });
        });
    </script>
@endsection
