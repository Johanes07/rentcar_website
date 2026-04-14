<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rental</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f59e0b; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        h2 { color: #333; }
    </style>
</head>
<body>
    <h2>Laporan Rental Mobil</h2>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mobil</th>
                <th>Customer</th>
                <th>Tanggal Sewa</th>
                <th>Tanggal Kembali</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rentals as $i => $rental)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $rental->car->nama ?? '-' }}</td>
                <td>{{ $rental->customer->nama ?? '-' }}</td>
                <td>{{ $rental->tanggal_sewa }}</td>
                <td>{{ $rental->tanggal_kembali }}</td>
                <td>Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"><strong>Total Pendapatan</strong></td>
                <td><strong>Rp {{ number_format($rentals->sum('total_harga'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>