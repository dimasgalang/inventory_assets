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
                    <h1 class="h3 mb-0 text-gray-800">Create Machine QR</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create Machine QR</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('machineqr.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif

                            @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif

                            @if ($message = Session::get('warning'))
                            <div class="alert alert-warning alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif

                            @if ($message = Session::get('info'))
                            <div class="alert alert-info alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif
                            <div>
                                <label>Customs Code :</label>
                                <select class="form-control customs_code" id="customs_code" name="customs_code" >
                                    <option></option>
                                    @foreach ($items as $item )
                                        <option value="{{ $item->barang_code }}">{{ $item->barang_code }} - {{ $item->barang_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Machine Code :</label>
                                <input class="form-control" type="text" id="machine_code" name="machine_code" required>
                            </div>
                            <br>
                            <div>
                                <label>Brand :</label>
                                <input class="form-control" type="text" id="brand" name="brand" required>
                            </div>
                            <br>
                            <div>
                                <label>Type :</label>
                                <input class="form-control" type="text" id="type" name="type" required>
                            </div>
                            <br>
                            <div>
                                <label>Machine Name :</label>
                                <input class="form-control" type="text" id="machine_name" name="machine_name" required readonly>
                            </div>
                            <br>
                            <div>
                                <label>Serial Number :</label>
                                <input class="form-control" type="text" id="serial_number" name="serial_number" required>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <button id="submit" type="submit" class="btn btn-primary btn-block">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Content Row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

@include('layout.footer')
</body>

<script type="text/javascript">
    $('.customs_code').select2({
          allowClear: true,
          placeholder: 'Choose Product Item',
    });

    $(document).on("change", "#customs_code", function(e){
            e.preventDefault();
            var customs_code = $(this).val();
            if (customs_code) {
                $.ajax({
                    url: '/smartit/getitem/'+customs_code,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#machine_name').val(data[0].barang_name);
                    }
                });
            } else{
                $('#machine_name').empty();
            }
        });
</script>
</html>