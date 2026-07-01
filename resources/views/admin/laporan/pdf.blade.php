<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            margin: 25px 20px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #1f2937;
        }
        .header {
            text-align: center;
            margin-bottom: 14px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 8px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 2px 0;
            color: #1e3a8a;
        }
        .header p {
            font-size: 9px;
            color: #6b7280;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            background-color: #2563eb;
            color: #ffffff;
            font-size: 9px;
            text-align: left;
            padding: 6px 5px;
            border: 1px solid #1e40af;
        }
        tbody td {
            font-size: 9px;
            padding: 5px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }
        tbody tr:nth-child(even) {
            background-color: #f3f4f6;
        }
        .empty {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
        }
        .footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $judul }}</h1>
        <p>Dicetak pada {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
    </div>

    @if(count($data) === 0)
        <p class="empty">Tidak ada data untuk ditampilkan.</p>
    @else
        <table>
            <thead>
                <tr>
                    @foreach($headings as $head)
                        <th>{{ $head }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        CDC IKIP &mdash; {{ $judul }} &mdash; Halaman <span></span>
    </div>
</body>
</html>
