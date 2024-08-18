<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Helpers\MapImageHelper;

class ReportController extends Controller
{
    public function generateReport()
    {
        Log::info('Starting report generation.');

        // JSON Data for Jayanta's marks and Assam performance
        $studentData = json_decode('{
    "student_name": "Jayanta Das",
    "class": "6 to 7",
    "marks": {
        "Math": 75,
        "Science": 80,
        "English": 70,
        "History": 85,
        "Geography": 90
    },
    "assam_performance": [
        {"master_district_id": "d1", "performance": 50},
        {"master_district_id": "d2", "performance": 200},
        {"master_district_id": "d3", "performance": 75},
        {"master_district_id": "d4", "performance": 300},
        {"master_district_id": "d5", "performance": 25},
        {"master_district_id": "d6", "performance": 123},
        {"master_district_id": "d7", "performance": 600},
        {"master_district_id": "d8", "performance": 450},
        {"master_district_id": "d9", "performance": 90},
        {"master_district_id": "d10", "performance": 15},
        {"master_district_id": "d11", "performance": 520},
        {"master_district_id": "d12", "performance": 340},
        {"master_district_id": "d13", "performance": 110},
        {"master_district_id": "d14", "performance": 85},
        {"master_district_id": "d15", "performance": 470},
        {"master_district_id": "d16", "performance": 700},
        {"master_district_id": "d17", "performance": 55},
        {"master_district_id": "d18", "performance": 280},
        {"master_district_id": "d19", "performance": 370},
        {"master_district_id": "d20", "performance": 95},
        {"master_district_id": "d21", "performance": 45},
        {"master_district_id": "d22", "performance": 130},
        {"master_district_id": "d23", "performance": 610},
        {"master_district_id": "d24", "performance": 230},
        {"master_district_id": "d25", "performance": 480},
        {"master_district_id": "d26", "performance": 160},
        {"master_district_id": "d27", "performance": 290},
        {"master_district_id": "d28", "performance": 330},
        {"master_district_id": "d29", "performance": 75},
        {"master_district_id": "d30", "performance": 190},
        {"master_district_id": "d31", "performance": 670},
        {"master_district_id": "d32", "performance": 540},
        {"master_district_id": "d33", "performance": 220},
        {"master_district_id": "d34", "performance": 430},
        {"master_district_id": "d35", "performance": 310}
    ]
}', true);

        // Now $studentData contains the decoded associative array
        $studentName = $studentData['student_name'];
        $class = $studentData['class'];
        $marks = $studentData['marks'];
        $assamPerformance = $studentData['assam_performance'];

        // Generate the chart image (assuming this function still returns a base64 PNG string)
        Log::info('Calling exportChartImage.');
        $chartImage = $this->exportChartImage($marks, $studentName);
        Log::info('Chart image generated.');

        // Generate the Assam map as a base64-encoded SVG using procedural function
        Log::info('Calling generateAssamMap.');
        $assamMapSvg = generateAssamMap($assamPerformance);
        Log::info('Assam map generated.');

        // Generate and return the PDF
        Log::info('Calling generatePdf.');
        return $this->generatePdf($studentName, $class, $marks, $chartImage, $assamMapSvg);
    }
    protected function renderForExport($marks, $studentName)
    {
        Log::info('Rendering options for export.');

        $output = [
            'chart' => [
                'type' => 'column',
            ],
            'title' => ['text' => null],
            'subtitle' => ['text' => null],
            'credits' => ['enabled' => false],
            'xAxis' => [
                'categories' => array_keys($marks)
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'Marks'
                ]
            ],
            'legend' => new \stdClass(),
            'plotOptions' => new \stdClass(),
            'series' => [
                [
                    'name' => $studentName,
                    'data' => array_values($marks)
                ]
            ]
        ];

        Log::info('Options rendered for export: ', $output);
        return json_encode($output);
    }

    protected function exportChartImage($marks, $studentName)
    {
        Log::info('Starting exportChartImage process.');

        $options = $this->renderForExport($marks, $studentName);

        // Generate the PNG content
        $url = 'https://export.highcharts.com/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=image/png&width=900&options=' . $options);
        curl_setopt($ch, CURLOPT_POST, 1);

        Log::info('Sending request to Highcharts export server.');
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);

        Log::info('Response received from Highcharts export server.');

        // Save the image as a PNG
        $file = storage_path('app/public/chart.png');
        file_put_contents($file, $response);

        return base64_encode(file_get_contents($file));
    }

    protected function generatePdf($studentName, $class, $marks, $chartImage, $assamMapSvg)
    {
        Log::info('Generating PDF.');
        Log::info('Map SVG : ' . $assamMapSvg);
        Log::info('Chart Image : ' . $chartImage);

        // Pass data to the Blade view and generate the PDF
        $pdf = Pdf::loadView('reports.student', compact('studentName', 'class', 'marks', 'chartImage', 'assamMapSvg'))
            ->setPaper('a4', 'portrait'); // Adjust paper size and orientation

        Log::info('PDF generated, initiating download.');

        return $pdf->download('student-report.pdf');
    }
}
