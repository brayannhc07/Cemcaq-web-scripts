<?php

include_once '../helpers/read-csv.php';
const DATETIME_COLUMN = "Date_Time";
$filePath       = '../data/historico_ica.csv';
$categoriesPath = '../data/categories.csv';
$intervalsPath  = '../data/intervals.csv';

$type     = 'line';
$from     = isset($_GET['from']) ? $_GET['from'] : '';
$to       = isset($_GET['to']) ? $_GET['to'] : '';
$variable = isset($_GET['variable']) ? $_GET['variable'] : [];

try {
    $categories        = readCsvToArray($categoriesPath, "Id");
    $intervals         = readCsvToArray($intervalsPath);
    $intervals         = array_filter($intervals,
        function ($interval) use ($variable) {
            return $interval['IndexCode'] === str_replace('PM25', 'PM2.5',
                    $variable);
        });
    $intervals         = array_map(function ($interval) use ($categories) {
        $interval['ColorHex']
            = $categories[$interval['CategoryId']]['ColorHex'];

        return $interval;
    }, $intervals);
    $headerValues      = readCsvFileHeader($filePath, [DATETIME_COLUMN]);
    $componentNames    = array_unique(array_map(function ($header) {
        return explode('_', $header)[1];
    }, $headerValues));
    $variables         = array_filter($headerValues,
        function ($header) use ($variable) {
            return strpos($header, "_".$variable) !== false;
        });
    $measurementValues = csvToCustomAssocArray($filePath, DATETIME_COLUMN,
        $variables);

    $minDateTime       = min($measurementValues[DATETIME_COLUMN]);
    $minDateTime      = date('Y-m-d', strtotime($minDateTime));
    $maxDateTime       = max($measurementValues[DATETIME_COLUMN]);
    $maxDateTime      = date('Y-m-d', strtotime($maxDateTime));

    $measurementValues = filterDataByDateRange($measurementValues, DATETIME_COLUMN, $from." 00:00:00", $to." 23:59:59");
    $maxMeasurementValue = array_filter($measurementValues, function ($key) {
        return $key !== DATETIME_COLUMN;
    }, ARRAY_FILTER_USE_KEY);

    $maxMeasurementValue = max(array_map(function ($values) {
        return max($values);
    }, $maxMeasurementValue));
} catch (Exception $e) {
    echo $e->getMessage();
    $measurementValues   = [];
    $headerValues        = [];
    $componentNames      = [];
    $intervals           = [];
    $categories          = [];
    $maxMeasurementValue = 0;
    $minDateTime         = '';
    $maxDateTime         = '';
}
?>


<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css"
    rel="stylesheet"/>
  <title>ICA</title>
</head>
<body>
<div>
  <div class="flex m-4">
    <div class="flex-none">
      <form class="w-64 bg-white rounded-lg p-4 shadow-lg space-y-3">
        <div>
          <label for="from"
                 class="block mb-2 text-sm font-medium text-gray-900">
            Desde
          </label>
          <input type="date"
                 id="from"
                 name="from"
                 value="<?= $from ?: $minDateTime ?>"
                 min="<?= $minDateTime ?>"
                 max="<?= $maxDateTime ?>"
                 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                 placeholder="Fecha de Inicio" required/>
        </div>
        <div>
          <label for="to"
                 class="block mb-2 text-sm font-medium text-gray-900">
            Hasta
          </label>
          <input type="date"
                 id="to"
                 name="to"
                 value="<?= $to ?: $maxDateTime ?>"
                  min="<?= $minDateTime ?>"
                  max="<?= $maxDateTime ?>"
                 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                 placeholder="Fecha de Fin" required/>
        </div>
        <div>
          <label for="type"
                 class="block mb-2 text-sm font-medium text-gray-900">
            Variable
          </label>
          <select id="type"
                  name="variable"
                  required
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
              <?php
              foreach ($componentNames as $component) { ?>
                <option
                    <?= $variable == $component ? 'selected' : '' ?>
                  value="<?= $component ?>">
                    <?= $component ?>
                </option>
                  <?php
              } ?>
          </select>
        </div>
        <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
          Consultar
        </button>
      </form>
    </div>
    <div class="flex-1 p-8">
      <canvas id="myChart"></canvas>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script
  src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>
<script
  src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
  const ctx = document.getElementById('myChart');
  const maxMeasurementValue = <?= json_encode($maxMeasurementValue) ?>;
  const intervals = Object.values(<?= json_encode($intervals,
      JSON_NUMERIC_CHECK) ?>);
  const datetimeValues = <?= json_encode($measurementValues[DATETIME_COLUMN]) ?>;
  const measurementValues = <?= json_encode($measurementValues,
      JSON_NUMERIC_CHECK) ?>;

  const annotations = intervals.filter(interval => interval['FloorValue'] <= maxMeasurementValue)
    .map((interval, index, array) => {
      return {
        type: 'box',
        yMin: interval['FloorValue'],
        yMax: index === array.length - 1 ? maxMeasurementValue : array[index + 1]['FloorValue'],
        backgroundColor: interval['ColorHex'] + '09',
        borderColor: interval['ColorHex'],
        borderWidth: 1,
      };
    });
  const datasets = Object.keys(measurementValues)
    .filter(key => key !== <?= json_encode(DATETIME_COLUMN) ?>)
    .map((key) => {
      return {
        label: key,
        data: Object.values(measurementValues[key]),
        borderWidth: 2,
        tension: 0.3,
      };
    });

  new Chart(ctx, {
    type: <?= json_encode($type) ?>,
    data: {
      labels: datetimeValues,
      datasets: datasets,
    },
    options: {
      fill: false,
      interaction: {
        intersect: false,
      },
      radius: 0,
      scales: {
        y: {
          beginAtZero: true,
        },
      },
      plugins: {
        annotation: {
          common: {
            drawTime: 'beforeDraw',
          },
          annotations: annotations,
        },
      },
    },
  });
</script>
</body>
</html>
