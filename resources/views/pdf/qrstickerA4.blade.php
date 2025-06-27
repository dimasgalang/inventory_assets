<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['title'] }}</title>
    <style>
        @page { 
            size: 4in 3in;
            margin-top: 50px;
            margin-left: 0px;
            /* margin: 20px; margin-top: 40;  */
        }
        body { 
            margin-bottom: -100px;
            /* size: 6in 4in;  */
            width: 4in;
            height: 3in;
        }
    </style>
</head>
<body>
    <center>
    @foreach ($qrcodes as $qrcode)
        <img src="{{ storage_path('app/public/inventoryqr/' . $qrcode->qr_code) }}" style="width: 3cm; height: 3cm; border: 1px solid #000000; margin-bottom: 5px;">
    @endforeach
    </center>
</body>
</html>