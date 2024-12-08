@extends('Layouts.Report')
@section('jenis_laporan')
    <p>Laporan Peniaian Kinerja Guru Oleh Kepsesk</p>
@endsection
@section('content')
    @if ($showData == 'guru')
        @foreach ($data as $item)
            <div class="section">
                <div class="row">
                    <p class="label">Nama Guru</p>
                    <p>:</p>
                    <p>{{ $item->nama }}</p>
                </div>
                <div class="row">
                    <p class="label">NIP</p>
                    <p>:</p>
                    <p>{{ $item->nip }}</p>
                </div>
                <table class="table-container">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIP</th>
                            <th>Nama Penilai</th>
                            <th>Nama Kriteria</th>
                            <th>Bobot Kritria</th>
                            <th>Nilai</th>

                            <th>Tanggal Peniaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->detail_penilaian_kepsek as $key => $penilaian)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $penilaian->penilaian_kepesek->user->nip }}</td>
                                <td>{{ $penilaian->penilaian_kepesek->user->name }}</td>
                                <td>{{ $penilaian->kriteria->nama_kriteria }}</td>
                                <td>{{ $penilaian->kriteria->fuzzy }}</td>
                                <td style="text-align: center">{{ $penilaian->nilai }}</td>
                                <td>{{ $penilaian->created_at->format('D, d-M-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @elseif($showData == 'kriteria')
        <!-- Loopping berdasarkan Kriteria -->
        @foreach ($data as $item)
            <div class="section">
                <div class="row">
                    <p class="label">Nama Kriteria</p>
                    <p>:</p>
                    <p>{{ $item->nama_kriteria }}</p>
                </div>
                <div class="row">
                    <p class="label">Bobot Kriteria</p>
                    <p>:</p>
                    <p>{{ $item->bobot_kriteria }}%</p>
                </div>
                <div class="row">
                    <p class="label">Fuzzy</p>
                    <p>:</p>
                    <p>{{ $item->fuzzy }}</p>
                </div>
                <table class="table-container">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIP Penilai</th>
                            <th>Nama Penilai</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>Nilai</th>
                            <th>Tanggal Peniaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->detail_penilaian_kepsek as $key => $penilaian)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $penilaian->penilaian_kepesek->user->nip }}</td>
                                <td>{{ $penilaian->penilaian_kepesek->user->name }}</td>
                                <td>{{ $penilaian->guru->nip }}</td>
                                <td>{{ $penilaian->guru->nama }}</td>

                                <td style="text-align: center">{{ $penilaian->nilai }}</td>
                                <td>{{ $penilaian->created_at->format('D, d-M-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif

@endsection
