<h2>Data dari Excel</h2>

<table border="1">
    @foreach($rows as $row)
        <tr>
            @foreach($row as $cell)
                <td>{{ $cell }}</td>
            @endforeach
        </tr>
    @endforeach
</table>

<canvas id="myChart" style="width: 100%; height: 400px;"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels), // nama anak
            datasets: [{
                label: 'Total Aktivitas',
                data: @json($data),   // total aktivitas
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        }
    });
</script>
