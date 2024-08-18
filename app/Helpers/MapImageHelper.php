<?php

function generateAssamMap($data)
{
    // Load the original Assam map SVG from the public/svg directory
    $svgFilePath = public_path('svg/assamMap.svg');
    $svgContent = file_get_contents($svgFilePath);

    // Apply dynamic styles based on the provided data
    foreach ($data as $region) {
        $id = $region['master_district_id'];
        $performance = $region['performance'];
        $color = getColorForPerformance($performance);

        // Find the <path> element with the matching id and update the fill attribute
        $pattern = "/(<path[^>]*id=\"$id\"[^>]*)(fill=\"[^\"]*\")([^>]*>)/i";
        $replacement = "$1fill=\"$color\"$3";

        // If a matching path with a fill attribute is found, replace it
        if (preg_match($pattern, $svgContent)) {
            $svgContent = preg_replace($pattern, $replacement, $svgContent);
        } else {
            // If no fill attribute is present, add it to the <path> element
            $pattern = "/(<path[^>]*id=\"$id\"[^>]*)(>)/i";
            $replacement = "$1 fill=\"$color\"$2";
            $svgContent = preg_replace($pattern, $replacement, $svgContent);
        }
    }

    // Return the modified SVG content directly as a base64-encoded data URL
    return 'data:image/svg+xml;base64,' . base64_encode($svgContent);
}

function getColorForPerformance($performance)
{
    // Customize the color logic based on your requirements
    if ($performance >= 100) {
        return '#0acf7d'; // Green
    } elseif ($performance < 100 && $performance > 75) {
        return '#1DD8BD'; // Light Green
    } elseif ($performance <= 75 && $performance > 50) {
        return '#FFB92E'; // Yellow
    } elseif ($performance <= 50 && $performance > 25) {
        return '#FEF08A'; // Light Yellow
    } elseif ($performance <= 25 && $performance > 10) {
        return '#F472B6'; // Light Pink
    } else {
        return '#E83880'; // Red
    }
}
