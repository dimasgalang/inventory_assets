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
                    <h1 class="h3 mb-0 text-gray-800">Create Control Card</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create Control Card</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('controlcard.store') }}" enctype="multipart/form-data">
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
                                <label>Assets Number :</label>
                                <select class="form-control assets_number" id="assets_number" name="assets_number" >
                                    <option></option>
                                    @foreach ($items as $item )
                                        <option value="{{ $item->assets_number }}">{{ $item->assets_number }} - {{ $item->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Control Category :</label>
                                <select class="form-control control_category" id="control_category" name="control_category" >
                                    <option></option>
                                    @foreach ($services as $service )
                                        <option value="{{ $service->control_id }}">{{ $service->control_id }} - {{ $service->control_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Control Date :</label>
                                <input class="form-control" type="date" id="control_date" name="control_date" required>
                            </div>
                            <br>
                            <div>
                                <label>Control Price :</label>
                                <input class="form-control" type="number" id="control_price" name="control_price" required>
                            </div>
                            <br>
                            <div>
                                <label>Supplier :</label>
                                <select class="form-control supplier_code" id="supplier_code" name="supplier_code" >
                                    <option></option>
                                    @foreach ($suppliers as $supplier )
                                        <option value="{{ $supplier->supplier_code }}">{{ $supplier->supplier_code }} - {{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
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
    $('.assets_number').select2({
          allowClear: true,
          placeholder: 'Choose Assets Number',
    });
    $('.control_category').select2({
          allowClear: true,
          placeholder: 'Choose Category Services',
    });
    $('.supplier_code').select2({
          allowClear: true,
          placeholder: 'Choose Supplier',
    });
</script>
</html>