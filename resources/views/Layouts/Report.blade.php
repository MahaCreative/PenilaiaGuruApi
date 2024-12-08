<html lang="en">

<head>
    <title>Invoice</title>

    <style>
        body {
            padding: 2%;
        }

        .container {
            padding: 3pt;
            width: 794px;
            height: 1123px;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px double black;
            padding-bottom: 3pt;
            margin-bottom: 1pt;
            line-height: 0.5rem;
        }

        .header img {
            display: inline-block;
            width: 50px;
            height: 50px;
            vertical-align: middle;
        }

        .header-content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-left: 1rem;
        }

        .header-content h1 {
            font-size: 1.5rem;
            font-weight: bold;

        }

        .header-content h3 {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .header-content p {
            font-size: 0.75rem;
            font-style: italic;
        }

        .details {
            display: table;
            width: 100%;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 0.5rem;
        }

        .details p {
            display: inline-block;
            margin-right: 0.5rem;
        }

        .section {
            border: 1px solid #00bcd4;
            border-radius: 0.25rem;
            padding: 1rem;
            font-size: 0.875rem;
            font-weight: 300;
            margin-bottom: 1rem;
            width: 100%;
        }

        .section .row {
            display: table;
            width: 100%;
            margin-bottom: -1rem;
        }



        .section .row .label {
            width: 150px;
            font-weight: 500;
        }

        .section .row p {

            display: inline-block;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table-container th,
        .table-container td {
            border: 1px solid #dafbff;
            padding: 0.5rem;
            text-align: left;
            text-transform: capitalize;
            font-size: 10pt;
        }

        .table-container th {
            font-weight: bold;
            text-transform: capitalize;
        }

        .table-container tbody tr:nth-child(odd) {
            background-color: #e0f7fa;
        }
    </style>
</head>

<body class='bg-blue-500'>
    <div class="container">
        <div class="header">
            <img src="http://127.0.0.1:8000/alchaeriyah.png" alt="">
            <div class="header-content">
                <h1>LAPORAN PENILAIAN KINERJA GURU</h1>
                <h3>MTsS Al-Chaeriyah Ma'arif Simboro</h3>
                <p>BTN, Jl. RE Martadinata Permai (H.Basir, Simboro, Kec. Simboro Dan Kepulauan, Kabupaten Mamuju,
                    Sulawesi Barat 91512</p>
            </div>
        </div>
        <div class="details">
            @yield('jenis_laporan')
            <p>Laporan Tanggal : {{ now()->format('D, d-M-Y') }}</p>
        </div>
        <div class="details">
            @if ($periode)
                <p>Periode Penilaian :{{ $periode->bulan . '-' . $periode->tahun }}</p>
                <p style="font-transform: capitalize;">Status Periode : {{ $periode->status }}</p>
            @else
                <p>Semua Periode Penilaian</p>
            @endif
        </div>
        @yield('content')



    </div>
</body>

</html>
