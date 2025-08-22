<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Dashboard</title>

    {{-- Bootstrap untuk layout grid --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <style>
        body {
            background: #f9fafb;
        }
        h1, h5 {
            margin-bottom: 15px;
        }
        .chart-box {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="container py-4">

    {{-- Header --}}
    <h1 class="text-center fw-bold">DASHBOARD EXCEL</h1>
    <h5 class="text-center text-muted">{{ $fileName }}</h5>

    {{-- Tabel Data --}}
    <div class="card my-4">
        <div class="card-body">
            <h4 class="mb-3">Data dari Excel</h4>
            <div class="table-responsive">
                <table id="excelTable" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($body as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart Row 1 --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-box">
                <canvas id="chart1"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-box">
                <canvas id="chart2"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart Row 2 --}}
    <div class="row">
        <div class="col-md-6">
            <div class="chart-box">
                <canvas id="chart3"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-box">
                <canvas id="chart4"></canvas>
            </div>
        </div>
    </div>

    {{-- jQuery + DataTables + Chart.js --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Init DataTables
        $(document).ready(function() {
            $('#excelTable').DataTable({
                scrollX: true
            });
        });

        // Chart 1: Top 5 Murid
new Chart(document.getElementById('chart1'), {
    type: 'bar',
    data: {
        labels: @json(array_slice($namaAnak, 0, 3)),
        datasets: [{
            label: 'Total Aktivitas',
            data: @json(array_slice($totalAktivitas, 0, 3)),
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    }
});

// Chart 2: Top 5 Wali
new Chart(document.getElementById('chart2'), {
    type: 'bar',
    data: {
        labels: @json(array_slice($topWaliLabels, 0, 3)),
        datasets: [{
            label: 'Jumlah Aktivitas Murid',
            data: @json(array_slice($topWaliData, 0, 3)),
            backgroundColor: 'rgba(255, 99, 132, 0.7)'
        }]
    }
});

// Chart 3: Line
new Chart(document.getElementById('chart3'), {
    type: 'line',
    data: {
        labels: @json(array_slice($namaAnak, 0, 10)),
        datasets: [{
            label: 'Jumlah Kegiatan',
            data: @json(array_slice($jumlahKegiatan, 0, 10)),
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false
        }]
    }
});

// Chart 4: Pie
new Chart(document.getElementById('chart4'), {
    type: 'pie',
    data: {
        labels: @json(array_slice($namaWali, 0, 5)),
        datasets: [{
            label: 'Jumlah Kunjungan',
            data: @json(array_slice($jumlahKunjungan, 0, 5)),
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)'
            ]
        }]
    }
});
    </script>

</body>
</html>