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
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .kartu-kontrol {
            width: 450px;
        }

        .kartu-kontrol h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            text-decoration: underline;
        }

        .info p {
            font-size: 14px;
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            text-align: center;
            padding: 6px;
            height: 28px;
        }
    </style>
</head>
<body>
    <center>
        <div class="kartu-kontrol">
            <h2>CONTROL CARD</h2>

            <div class="info">
                <p><strong>Nama Perangkat</strong> : {{ $controlcards[0]->assets_number }} - {{ $controlcards[0]->item_name }} ({{ $controlcards[0]->location }})</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Tgl</th>
                        <th>Layanan</th>
                        <th>Biaya</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($controlcards as $controlcard)
                    <tr>
                        <td>
                            {{ $controlcard->control_date }}
                        </td>
                        <td>
                            {{ $controlcard->control_name }}
                        </td>
                        <td>
                            {{ $controlcard->control_price }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </center>
</body>
</html>