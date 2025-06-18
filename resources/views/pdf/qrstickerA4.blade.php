<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['title'] }}</title>
    <style>
        @page { margin: 20px; margin-top: 40; }
        body { margin: 20px; }
    </style>
</head>
<body>
    @foreach ($qrcodes as $qrcode)
        <img src="{{ storage_path('app/public/inventoryqr/' . $qrcode->qr_code) }}" style="width: 2cm; height: 2cm; border: 1px solid #000000; margin-bottom: 5px;">
    @endforeach
</body>
</html>