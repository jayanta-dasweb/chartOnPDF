<!DOCTYPE html>
<html>

<head>
    <title>Student Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            text-align: center;
            padding: 20px;
            margin-bottom: 50px;
            /* Space for footer */
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

        /* Footer styles */
        @page {
            margin: 10mm 10mm -3mm 10mm;
            /* Space for the footer */
        }

        .footer {
            position: fixed;
            bottom: 0px;
            /* Fixed to bottom of the page */
            left: 0;
            right: 0;
            height: 30px;
            /* Adjust height as needed */
            text-align: center;
            font-size: 8px;
        }
    </style>
</head>

<body>

<div class="footer">
    Report generated date: {{ \Carbon\Carbon::now()->format('d-m-Y') }}
</div>
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

    <!-- Footer -->
    
</body>

</html>