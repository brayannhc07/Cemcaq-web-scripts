<?php

include_once '../helpers/read-csv.php';
const DATETIME_COLUMN = "Date_Time";
$filePath  = '../data/DatosCompletos.csv';
$type      = isset($_GET['type']) ? $_GET['type'] : 'line';
$from      = isset($_GET['from']) ? $_GET['from'] : '';
$to        = isset($_GET['to']) ? $_GET['to'] : '';
$variables = isset($_GET['variables']) ? $_GET['variables'] : [];
try {
    $headerValues      = readCsvFileHeader($filePath, [DATETIME_COLUMN]);
    $measurementValues = csvToCustomAssocArray($filePath, DATETIME_COLUMN,
        $variables, $from." 00:00:00", $to." 23:59:59");
} catch (Exception $e) {
    echo $e->getMessage();
    $measurementValues = [];
    $headerValues      = [];
}
?>

<link
  href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css"
  rel="stylesheet"/>

<div>
  <div class="flex m-4">
    <div class="flex-none">
      <form class="w-64 bg-white rounded-lg p-4 shadow-lg space-y-3">
        <div>
          <label for="type"
                 class="block mb-2 text-sm font-medium text-gray-900">
            Tipo de gráfica
          </label>
          <select id="type"
                  name="type"
                  required
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="line">Line</option>
            <option value="bar">Bar</option>
          </select>
        </div>
        <div>
          <label for="from"
                 class="block mb-2 text-sm font-medium text-gray-900">
            Desde
          </label>
          <input type="date"
                 id="from"
                 name="from"
                 value="<?= $from ?>"
                 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                 placeholder="John" required/>
        </div>
        <div>
          <label for="to"
                 class="block mb-2 text-sm font-medium text-gray-900">
            Hasta
          </label>
          <input type="date"
                 id="to"
                 name="to"
                 value="<?= $to ?>"
                 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                 placeholder="John" required/>
        </div>
        <div>
          <p class="block mb-2 text-sm font-medium text-gray-900">
            Variables
          </p>
          <div class="grid grid-cols-2">
              <?php
              foreach ($headerValues as $variable) { ?>
                <div class="flex items-center mb-4">
                  <input id="variable-<?= $variable ?>"
                         type="checkbox"
                         value="<?= $variable ?>"
                      <?= in_array($variable, $variables) ? 'checked' : '' ?>
                         name="variables[]"
                         class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                  <label for="variable-<?= $variable ?>"
                         class="ms-2 text-sm font-medium text-gray-900"><?= $variable ?></label>
                </div>
                  <?php
              } ?>
          </div>
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
  src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
  const ctx = document.getElementById('myChart');
  const datetimeValues = <?= json_encode($measurementValues[DATETIME_COLUMN]) ?>;
  const measurementValues = <?= json_encode($measurementValues,
      JSON_NUMERIC_CHECK) ?>;
  const datasets = Object.keys(measurementValues)
    .filter(key => key !== <?= json_encode(DATETIME_COLUMN) ?>)
    .map((key) => {
    return {
      label: key,
      data: Object.values(measurementValues[key]),
      borderWidth: 1,
    };
  });

  new Chart(ctx, {
    type: <?= json_encode($_GET['type']) ?>,
    data: {
      labels: datetimeValues,
      datasets: datasets,
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
</script>
