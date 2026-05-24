<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data) {
    $file = fopen('vesc_data.csv', 'a');
    foreach ($data as $entry) {
        fputcsv($file, [date('Y-m-d H:i:s'), $entry['rpm'], $entry['amp'], $entry['volt']]);
    }
    fclose($file);
    echo json_encode(['status' => 'saved']);
}
?>