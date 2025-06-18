<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['title'] }}</title>
    <style>
        @page { 
            size: 8.5in 13in;
            margin-left: 10px;
            /* margin: 20px; margin-top: 40;  */
        }
        body { 
            margin: 0px; 
            width: 8.5in;
            height: 13in;
        }
    </style>
</head>
<body>
    @foreach ($qrcodes as $qrcode)
        <img src="{{ storage_path('app/public/inventoryqr/' . $qrcode->qr_code) }}" style="width: 4cm; height: 4cm; border: 1px solid #000000; margin-bottom: 5px;">
    @endforeach
</body>
</html>