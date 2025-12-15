@extends('admin.masterAdmin')
@section('title', 'Laporan Permintaan')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Permintaan</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Periode</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.permintaan') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select name="bulan" class="form-control">
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
                                <label>Tahun</label>
                                <select name="tahun" class="form-control">
                                    @for($i = 2024; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Data Semua Permintaan</h3>
            </div>
            <div class="card-body">
                <table id="tabelPermintaan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Paket</th>
                            <th>Status Order</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataLaporan as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggalPemesanan)->format('d/m/Y') }}</td>
                            <td>{{ $row->user->namaLengkap ?? '-' }}</td>
                            <td>{{ $row->slotJadwal->paketLayanan->namaPaket ?? '-' }}</td>
                            <td>
                                {{-- Logic Warna Badge Status --}}
                                @php
                                    $badgeClass = match($row->statusPemesanan) {
                                        'selesai' => 'badge-success',
                                        'disetujui' => 'badge-info',
                                        'dibatalkan' => 'badge-danger',
                                        default => 'badge-warning'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($row->statusPemesanan) }}</span>
                            </td>
                            <td>{{ ucfirst($row->statusPembayaran) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Statistik Status Permintaan</h3>
                <div class="card-tools">
                    <button onclick="downloadChart()" class="btn btn-sm btn-info ml-2">
                        <i class="fas fa-download mr-1"></i> Download Chart
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        {{-- Canvas Chart --}}
                        <canvas id="statusChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(function () {
        // A. Setup DataTable
        $("#tabelPermintaan").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["csv", "pdf", "print"]
        }).buttons().container().appendTo('#tabelPermintaan_wrapper .col-md-6:eq(0)');

        // B. Setup Chart (Doughnut)
        const ctx = document.getElementById('statusChart').getContext('2d');

        // Ambil data dari controller (cara aman JSON.parse)
        const labels = JSON.parse('@json($chartLabels)');
        const values = JSON.parse('@json($chartValues)');

        // Mapping Warna biar konsisten
        // Kita cocokin labelnya buat nentuin warna
        const colorMap = {
            'Pending': '#ffc107',    // Kuning
            'Disetujui': '#17a2b8',  // Biru Muda
            'Selesai': '#28a745',    // Hijau
            'Dibatalkan': '#dc3545', // Merah
        };

        // Bikin array warna berdasarkan urutan labels
        const bgColors = labels.map(label => colorMap[label] || '#6c757d'); // Default abu-abu kalo ga nemu

        const chart = new Chart(ctx, {
            type: 'doughnut', // Chart bentuk donat
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: bgColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom', // Legenda di bawah
                    }
                }
            }
        });
    });

    // C. Download Chart
    function downloadChart() {
        var link = document.createElement('a');
        link.download = 'Grafik-Permintaan.png';
        link.href = document.getElementById('statusChart').toDataURL('image/png');
        link.click();
    }
</script>
@endsection
