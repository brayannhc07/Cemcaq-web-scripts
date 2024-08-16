<?php

include_once '../helpers/read-csv.php';

$data           = readCsvToArray('../data/BD_SMCAQ.csv');
$dateColumnName = 'date';

if ($data === false) {
    echo 'Error al leer el archivo CSV';
    exit;
}

$begin     = isset($_GET['begin']) ? $_GET['begin'] : '';
$end       = isset($_GET['end']) ? $_GET['end'] : '';
$station   = isset($_GET['station']) ? $_GET['station'] : '';
$parameter = isset($_GET['parameter']) ? $_GET['parameter'] : '';

$startDate = DateTime::createFromFormat('Y-m-d H:i:s', $begin.' 00:00:00');
$endDate   = DateTime::createFromFormat('Y-m-d H:i:s', $end.' 23:59:59');

if($startDate === false || $endDate === false) {
    echo 'Error al leer las fechas';
    exit;
}

if($startDate >= $endDate) {
    echo 'La fecha de inicio debe ser menor a la fecha final';
    exit;
}

// get columns
$columnNames = array_keys($data[0]);

if ( ! empty($station)) {

    $columnNames = array_filter($columnNames, function ($key) use ($station) {
        return strpos($key, $station.'_') === 0;
    });
    array_unshift($columnNames, $dateColumnName);
} elseif ( ! empty($parameter)) {
    $columnNames = array_filter($columnNames, function ($key) use ($parameter) {
        return substr($key, -strlen('_' . $parameter)) === '_' . $parameter;
    });
    array_unshift($columnNames, $dateColumnName);
}

$filename = 'datos.csv';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$filename.'"');
$output = fopen('php://output', 'w');
fputcsv($output, $columnNames);

foreach ($data as $index => $row) {
    $dateTime = DateTime::createFromFormat('d/m/Y H:i', $row['date']);
    if ($dateTime >= $startDate && $dateTime <= $endDate) {
        // Output the row
        $row = $row = array_intersect_key($row, array_flip($columnNames));
        fputcsv($output, $row);
    }
}

// Close the file pointer
fclose($output);

// Exit to prevent any further output
exit;
