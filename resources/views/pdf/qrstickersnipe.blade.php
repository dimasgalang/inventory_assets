<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['title'] }}</title>
    <style>
        @page { 
            /* size: 4in 6in; */
            size: 4in 3in;
            /* margin-top: 50px; */
            margin-right: 10px;
            /* margin: 20px; margin-top: 40;  */
        }
        body { 
            /* margin-bottom: -100px; */
            /* size: 6in 4in;  */
            width: 4in;
            height: 3in;
            /* width: 4in;
            height: 6in; */
        }

        @media print {
            @page {
                padding: 10px;
            }
            @page:first {
                padding-top: 5px;
            }
        }

        .qr-container {
        display: grid;
        grid-template-columns: repeat(3, 4fr);
        gap: 5px;
        }

        .qr-item {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
        }

        .qr-label {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 14px;
        }

        .qr-item img {
        width: 100%;
        height: auto;
        }

        .page-break {
            page-break-after: always;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <center>
        <div class="qr-container">
            @foreach ($qrcodes as $qrcode)
            <div class="qr-item">
                <div class="qr-label">{{ $qrcode->asset_tag }}</div>
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->generate('http://192.168.1.243/hardware/' . $qrcode->id)) !!} " style="width: 2.5cm; height: 2.5cm">
            </div>

                @php
                    if($loop->iteration % 6 == 0){
                @endphp
                    </div>
                        <div class="page-break"></div>
                    <div class="qr-container">
                @php
                    }
                @endphp
            @endforeach
        </div>
    </center>
</body>
</html>