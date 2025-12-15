@extends('admin.masterAdmin')
@section('title', 'Laporan Pendapatan')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Pendapatan</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.pendapatan') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pilih Bulan</label>
                                <select name="bulan" class="form-control">
                                    {{-- Looping angka 1-12 buat bulan --}}
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                        @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pilih Tahun</label>
                                <select name="tahun" class="form-control">
                                    {{-- Looping tahun dari 2024 sampai tahun sekarang --}}
                                    @for($i = 2024; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search mr-1"></i> Tampilkan Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title font-weight-bold">
                    Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h3>
            </div>
            <div class="card-body">
                <table id="tabelPendapatan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>No Booking</th>
                            <th>Customer</th>
                            <th>Paket Layanan</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ubah @forelse jadi @foreach biasa, dan HAPUS bagian @empty --}}
                        @foreach($dataLaporan as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggalPemesanan)->format('d/m/Y') }}</td>
                            <td><span class="badge badge-light">{{ $row->nomorBooking }}</span></td>
                            <td>{{ $row->user->namaLengkap ?? 'User Terhapus' }}</td>
                            <td>{{ $row->slotJadwal->paketLayanan->namaPaket ?? 'Paket Terhapus' }}</td>
                            <td>Rp {{ number_format($row->totalHarga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Grafik Pendapatan Harian</h3>
                <div class="card-tools">
                    {{-- Tombol Download Gambar --}}
                    <button onclick="downloadChart()" class="btn btn-sm btn-info ml-2">
                        <i class="fas fa-download mr-1"></i> Download Grafik
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    {{-- Canvas tempat grafik digambar --}}
                    <canvas id="revenueChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(function() {
        // A. Setup Tabel biar bisa Export PDF/CSV
        $("#tabelPendapatan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["csv", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabelPendapatan_wrapper .col-md-6:eq(0)');


        // B. Setup Grafik (Chart.js)
        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Data dari Controller dilempar kesini pake json_encode
        const labels = JSON.parse('@json($chartLabels)');
        const values = JSON.parse('@json($chartValues)');

        const chart = new Chart(ctx, {
            type: 'line', // Jenis grafik garis
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: values,
                    backgroundColor: 'rgba(40, 167, 69, 0.2)', // Warna Hijau Transparan
                    borderColor: 'rgba(40, 167, 69, 1)', // Garis Hijau
                    borderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.3 // Biar garisnya agak melengkung dikit
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Format angka di sumbu Y jadi Rupiah
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });

    // C. Fungsi Download Grafik jadi Gambar
    function downloadChart() {
        var link = document.createElement('a');
        link.download = 'Grafik-Pendapatan.png';
        link.href = document.getElementById('revenueChart').toDataURL('image/png');
        link.click();
    }
</script>
@endsection
