<!DOCTYPE html>
<html>

<head>
    <title>Student Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            width: 100%;
            text-align: center;
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        h1,
        h2 {
            margin: 0;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Report for {{ $studentName }}</h1>
        <h2>Class: {{ $class }}</h2>

        <!-- Display the PNG chart -->
        <div class="chart">
            <img src="data:image/png;base64,{{ $chartImage }}" alt="Chart Image" style="max-width: 100%; height: auto;">
        </div>

        <!-- Display the Assam Map PNG -->
        <h2>Assam District Performance</h2>
        <div class="map">
            <img src="{{ $assamMapSvg }}" alt="Assam Map" style="max-width: 100%; height: auto;">
        </div>


        <h2>Marks Table</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($marks as $subject => $mark)
                    <tr>
                        <td>{{ $subject }}</td>
                        <td>{{ $mark }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
