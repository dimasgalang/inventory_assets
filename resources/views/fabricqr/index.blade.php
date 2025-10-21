<!DOCTYPE html>
<html lang="en">
@include('layout.header')
<body id="page-top">
<!-- Page Wrapper -->
@include('sweetalert::alert')
<div id="wrapper">
@include('layout.sidebar')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            @include('layout.navbar')
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Fabrics QR List</h1>
                    <div>
                        <form method="GET" action="{{ route('fabricqr.batchqr') }}" >
                            <button id="submit" type="submit" class="btn btn-sm btn-primary shadow-s"><i
                            class="fas fa-qrcode fa-sm text-white-50"></i> Generate QR</a></button>
                        <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#importModal"><i
                            class="fas fa-upload fa-sm text-white-50"></i> Upload Data</a>
                        <!-- <a href="{{ route('fabricqr.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-plus fa-sm text-white-50"></i> Create Inventory</a> -->
                        <!-- <a href="{{ route('pdf.generatePDF') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Download Sticker</a> -->
                        <a href="" data-toggle="modal" data-target="#stickerModal" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Download Sticker</a>
                        </form>
                    </div>
                </div>
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
                        <h6 class="m-0 font-weight-bold text-primary">Fabrics QR Data</h6>
                        <form method="GET" id="form-void">
                                <select name="void" id="void" class="form-control" onchange="document.getElementById('form-void').submit()" style="width: 300px;">
                                    <option disabled selected hidden>Select Status</option>
                                    <option value="false" {{ app('request')->input('void') == 'false'  ? 'selected' : ''}}>Active</option>
                                    <option value="true" {{ app('request')->input('void') == 'true'  ? 'selected' : ''}}>Void</option>
                                </select>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier</th>
                                        <th>Contract No</th>
                                        <th>delivery_note_no</th>
                                        <th>invoice_no</th>
                                        <th>style</th>
                                        <th>item_code</th>
                                        <th>item_name</th>
                                        <th>item_category</th>
                                        <th>random_qr_code</th>
                                        <th>QR Code</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fabricqrs as $fabricqr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $fabricqr->supplier }}</td>
                                        <td>{{ $fabricqr->contract_no }}</td>
                                        <td>{{ $fabricqr->delivery_note_no }}</td>
                                        <td>{{ $fabricqr->invoice_no }}</td>
                                        <td>{{ $fabricqr->style }}</td>
                                        <td>{{ $fabricqr->item_code }}</td>
                                        <td>{{ $fabricqr->item_name }}</td>
                                        <td>{{ $fabricqr->item_category }}</td>
                                        <td>{{ $fabricqr->random_qr_code }}</td>
                                        <td class="text-center"><img id="qr" src="{{url('/storage/fabricsqr/'. $fabricqr->qr_code)}}" style="width: 150;"></td>
                                        {{-- <td class="text-center">
                                            @if (request()->get('void') == 'false' || request()->get('void') == '')
                                            <a href ="{{ route('fabricqr.generateqr', ['id' => $fabricqr->id]) }}" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-qrcode"></i>
                                            </a>
                                            <a class="btn btn-danger btn-circle btn-sm btn-void-record" data-void-link="{{ route('fabricqr.void', ['id' => $fabricqr->id]) }}" data-void-name="{{ $fabricqr->item_name }}" data-toggle="modal" data-target="#voidModal">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                            @elseif (request()->get('void') == 'true')
                                            <a class="btn btn-success btn-circle btn-sm btn-restore-record" data-restore-link="{{ route('fabricqr.restore', ['id' => $fabricqr->id]) }}" data-restore-name="{{ $fabricqr->item_name }}" data-toggle="modal" data-target="#restoreModal">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Content Row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Modal -->


        <div class="modal fade" id="stickerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="fabricqr" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="sticker-title" class="modal-title" id="exampleModalLabel">Export Sticker</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="GET" action="{{ route('pdf.generatePDF') }}">
                            @csrf
                            <div>
                                <label>Export Type :</label>
                                <select class="form-control exporttype" id="exporttype" name="exporttype">
                                    <!-- <option></option> -->
                                    <option value="range">Range Assets Number</option>
                                    <option value="all">All Assets Number</option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>From :</label>
                                <select class="form-control" id="from_assets_number" name="from_assets_number" required>
                                    <option></option>
                                    @foreach ($fabricqrs as $fabricqr )
                                        <option value="{{ $fabricqr->assets_number }}">{{ $fabricqr->assets_number }} - {{ $fabricqr->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>To :</label>
                                <select class="form-control" id="to_assets_number" name="to_assets_number" required>
                                    <option></option>
                                    @foreach ($fabricqrs as $fabricqr )
                                        <option value="{{ $fabricqr->assets_number }}">{{ $fabricqr->assets_number }} - {{ $fabricqr->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <button class="btn btn-success" type="submit">Export</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="fabricqr" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="pdf-title" class="modal-title" id="exampleModalLabel">Inventory QR Name</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body"><iframe id="pdf-src" src ="" width="100%" height="480px"></iframe></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="fabricqr" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="delete-title" class="modal-title" id="exampleModalLabel">Delete Record</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body"><p id="modal-text-record"></p></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a id="btn-confirm" href=""><button class="btn btn-primary" type="button">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="voidModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="fabricqr" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="void-title" class="modal-title" id="exampleModalLabel">Void Record</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body"><p id="modal-text-record-void"></p></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a id="btn-confirm-void" href=""><button class="btn btn-danger" type="button">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="fabricqr" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="restore-title" class="modal-title" id="exampleModalLabel">Restore Record</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body"><p id="modal-text-record-restore"></p></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a id="btn-confirm-restore" href=""><button class="btn btn-success" type="button">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Import Fabrics Assets</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <form action="{{ route('fabricqr.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>PILIH FILE</label>
                                    <input type="file" name="file" accept=".xls,.xlsx">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>


@include('layout.footer')
</body>
<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Page level custom scripts -->
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $('.btn-show-pdf').on('click', function () {
        $('#pdf-src').attr('src', '../../storage/fabricqr/' + $(this).data('show-link'));
        $("#pdf-title").text($(this).data('show-title'));
    });
    $('.btn-delete-record').on('click', function () {
            $('#btn-confirm').attr('href', $(this).data('delete-link'));
            $("#modal-text-record").text('Apakah anda yakin ingin menghapus Inventory QR ' + $(this).data('delete-name') + '?');
    });
    $('.btn-void-record').on('click', function () {
            $('#btn-confirm-void').attr('href', $(this).data('void-link'));
            $("#modal-text-record-void").text('Apakah anda yakin ingin menghapus Inventory QR ' + $(this).data('void-name') + '?');
    });
    $('.btn-restore-record').on('click', function () {
            $('#btn-confirm-restore').attr('href', $(this).data('restore-link'));
            $("#modal-text-record-restore").text('Apakah anda yakin ingin mengembalikan Inventory QR ' + $(this).data('restore-name') + '?');
    });
    $("#submit").click(function() {
        $(this).hide();
        Swal.fire({
            title: "Process",
            html: "Generating All QR Code.. Please Wait!!",
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            },
        })
    });
    $('#from_assets_number').select2({
          allowClear: true,
          placeholder: 'Choose Assets Number',
          dropdownParent: $("#stickerModal")
    });
    $('#to_assets_number').select2({
          allowClear: true,
          placeholder: 'Choose Assets Number',
          dropdownParent: $("#stickerModal")
    });
    $('.exporttype').select2({
          allowClear: true,
          placeholder: 'Choose Export Type',
    });
    $(document).on("change", "#exporttype", function(e){
        e.preventDefault();
        var type = $(this).val();
        if (type == "range") {
            $('#from_assets_number').removeAttr('disabled');
            $('#to_assets_number').removeAttr('disabled');
            $('#from_assets_number').required = true;
            $('#to_assets_number').required = true;
        } else{
            $('#from_assets_number').val('').trigger('change');
            $('#to_assets_number').val('').trigger('change');
            $('#from_assets_number').attr('disabled','disabled');
            $('#to_assets_number').attr('disabled','disabled');
            $('#from_assets_number').required = false;
            $('#to_assets_number').required = false;
        }
    });
</script>
</html>