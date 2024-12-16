@extends('Layouts.Report')
@section('jenis_laporan')
    <p>Laporan Penilaian Guru</p>
@endsection
@section('content')
    {{-- showRankTotal --}}
    {{-- showRankKepsek --}}
    {{-- showRankSiswa --}}
    {{-- shownormalisasiKepsek --}}
    {{-- shownormalisasiSiswa --}}
    {{-- Hasil Rank Total --}}
    <div style="padding: 5px 5px;" class="section">
        <div style="width: 100%">
            <p style="text-align: center; font-size: 15px; font-weight: bold">Laporan Hasil Rangking Penilaian Guru</p>
        </div>

        <table class="table-container">
            <thead>
                <tr>

                    <th>Ranking</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Hasil Nilai Kepsek</th>
                    <th>Hasil Nilai Siswa</th>
                    <th>Hasil Nilai Akhir</th>
                    <th>Tanggal Proses </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rankTotal as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->guru->nip }}</td>
                        <td>{{ $item->guru->nama }}</td>
                        <td>{{ $item->nilai_kepsek }}</td>
                        <td>{{ $item->nillai_siswa }}</td>
                        <td>{{ $item->skor_akhir }}</td>
                        <td>{{ $item->updated_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Hasil Rank Kepsek --}}
    <div style="padding: 5px 5px;" class="section">
        <div style="width: 100%">
            <p style="text-align: center; font-size: 15px; font-weight: bold">Laporan Hasil Rangking Penilaian Kepsek</p>
        </div>

        <table class="table-container">
            <thead>
                <tr>

                    <th>Ranking</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Hasil Skor</th>
                    <th>Tanggal Proses </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasilRanKepsek as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td>{{ $item->guru->nip }}</td>
                        <td>{{ $item->guru->nama }}</td>
                        <td>{{ $item->nilai_akhir }}</td>
                        <td>{{ $item->updated_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Normalisasi Penilaian Kesek --}}
    <div style="padding: 5px 5px;" class="section">
        <div style="width: 100%">
            <p style="text-align: center; font-size: 15px; font-weight: bold; line-height:3pt">Hasil Normalisasi Penilaian
                Kepsek</p>

        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Kriteria</th>
                    <th>Bobot Kriteria</th>
                    <th>Nilai</th>
                    <th>Hasil Normalisasi</th>
                    <th>Tanggal Proses</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($normalisasiKepsek as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td>{{ $item->guru->nip }}</td>
                        <td>{{ $item->guru->nama }}</td>
                        <td>{{ $item->kriteria->nama_kriteria }}</td>
                        <td>{{ $item->kriteria->bobot_kriteria }}%</td>
                        <td>{{ $item->detail_penilaian_kepsek->nilai }}</td>
                        <td>{{ $item->normalisasi }}</td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Hasil Rank Siswa --}}
    <div style="padding: 5px 5px;" class="section">
        <div style="width: 100%">
            <p style="text-align: center; font-size: 15px; font-weight: bold">Laporan Hasil Rangking Penilaian Siswa</p>
        </div>

        <table class="table-container">
            <thead>
                <tr>

                    <th>Ranking</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Hasil Skor</th>
                    <th>Jumlah Penilai</th>
                    <th>Tanggal Proses </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasilRankSiswa as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td>{{ $item->guru->nip }}</td>
                        <td>{{ $item->guru->nama }}</td>
                        <td>{{ $item->nilai_akhir }}</td>

                        <td>{{ $item->jumlah_siswa_menilai }} Siswa</td>
                        <td>{{ $item->updated_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
