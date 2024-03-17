<?php

// ../images/Reporte_calidad_aire/Reporte_horario/tablamapa.csv
$json        = file_get_contents('test.json');
$indexReport = json_decode($json, true);

$categoryArticleUrls = [
    1 => "http://aire.cemcaq.mx/buena-calidad-del-aire-riesgo-bajo/",
    2 => "http://aire.cemcaq.mx/recomendaciones/",
    3 => "http://aire.cemcaq.mx/mala-calidad-del-aire-riesgo-alto/",
    4 => "http://aire.cemcaq.mx/calidad-del-aire-muy-mala-riesgo-muy-alto/",
    5 => "http://aire.cemcaq.mx/extremadamente-mala-calidad-del-aire-riesgo-extremadamente-alto/",
];

$parameterArticleUrls = [
    "NO2"   => "http://aire.cemcaq.mx/dioxido-de-nitrogeno/",
    "CO"    => "http://aire.cemcaq.mx/monoxido-de-carbono/",
    "O3"    => "http://aire.cemcaq.mx/ozono/",
    "SO2"   => "http://aire.cemcaq.mx/dioxido-de-azufre/",
    "PM10"  => "http://aire.cemcaq.mx/material-particulado/",
    "PM2.5" => "http://aire.cemcaq.mx/material-particulado/",
];

$locationImageUrls = [
    "CAP" => "http://aire.cemcaq.mx/pronosticos/iconos/estacion/IconoCAP.png",
    "COR" => "http://aire.cemcaq.mx/pronosticos/iconos/estacion/IconoCOR.png",
    "EPG" => "http://aire.cemcaq.mx/pronosticos/iconos/estacion/IconoEPG.png",
    "FEO" => "http://aire.cemcaq.mx/pronosticos/iconos/estacion/IconoFEO.png",
    "JOV" => "http://aire.cemcaq.mx/pronosticos/iconos/estacion/IconoJOV.png",
    "SJU" => "http://aire.cemcaq.mx/pronosticos/iconos/estacion/IconoSJR.png",
];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <meta http-equiv="refresh" content="3600">
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css"
    rel="stylesheet"/>

</head>
<body>
<main class="container mx-auto p-2 md:p-4">
  <header class="text-center mb-8">
    <h2 class="text-lg font-normal">
      <span
        class="font-semibold">Última actualización</span>: <?= $indexReport["DateTime"] ?>
    </h2>
    <p class="text-sm text-gray-700 mb-6">
      Esta información se actualiza cada hora
    </p>
    <p class="text-gray-700">
      Haga clic sobre las celdas de la tabla para ver recomendaciones
    </p>
  </header>

  <div class="relative overflow-x-auto shadow-xl rounded-lg mb-10">
    <table
      class="w-full text-xs md:text-sm text-center text-gray-500 m-0">
      <thead
        class="text-gray-700 bg-gray-50">
      <tr>
        <th scope="col" class="p-1 md:p-2">
          Estación/<br>
          Contaminante
        </th>
          <?php
          foreach ($indexReport["Locations"] as $location) : ?>
            <th scope="col" class="px-0.5 py-2">
              <a href="http://aire.cemcaq.mx/informacion/"
                 class="flex flex-col items-center">
                <img
                  src="<?= $locationImageUrls[$location['Code']] ?>"
                  class="w-6 md:w-10 h-6 md:h-10"
                  alt="Logo de la estación <?= $location['Name'] ?>">
                <span class="text-sm md:text-base">
                    <?= $location["Code"] ?>
                </span>
                <span class="text-xs font-normal text-gray-500">
                    <?= $location["Name"] ?>
                </span>
              </a>
            </th>
          <?php
          endforeach ?>
      </tr>
      </thead>
      <tbody>
      <?php
      foreach ($indexReport["Indexes"] as $index) : ?>
        <tr
          class="bg-white border-b hover:bg-gray-50">
          <th scope="row"
              class="px-0.5 md:px-2 py-2 text-gray-900">
            <a class="text-xs md:text-sm md:text-base font-semibold"
               href="<?= $parameterArticleUrls[$index["ParameterCode"]] ?>">
                <?= $indexReport["Parameters"][$index["ParameterCode"]]["HtmlDisplay"] ?>
            </a>
            <p class="font-normal text-gray-500">
                <?= $index["Name"] ?>
            </p>
          </th>
            <?php
            foreach ($indexReport["Results"][$index["Code"]] as $result) : ?>
              <td class="px-3 py-2 font-medium"
                  style="background-color: <?= $indexReport['Categories'][$result['CategoryId']]['ColorHex'] ?>">
                <a href="<?= $categoryArticleUrls[$result['CategoryId']] ?>"
                   class="block text-gray-900"
                   title="Ver recomendaciones">
                    <?= $result["Index"] ?>
                </a>
              </td>
            <?php
            endforeach ?>
        </tr>
      <?php
      endforeach ?>
      </tbody>
    </table>
  </div>

  <section class="flex flex-wrap items-start justify-center">
      <?php
      foreach ($indexReport['Categories'] as $category) : ?>
        <div class="p-4 mr-2 w-32 flex flex-col items-center">
          <span class="flex w-10 h-10 rounded-full border-2 mb-2"
                style="background-color: <?= $category['ColorHex'] ?>"></span>
          <span
            class="text-center font-medium text-gray-700"><?= $category["Quality"] ?></span>
        </div>
      <?php
      endforeach ?>
  </section>

  <footer class="text-lg tracking-wide leading-relaxed">
    <p>Notas:</p>
    <p>
      Los índices de aire y salud (IAS) y las categorías del mismo, incluyendo
      las BANDAS CROMÁTICAS asociadas a la calidad del aire y nivel de riesgo,
      son las establecidas en la Norma Oficial Mexicana NOM-172-SEMARNAT-2019
      "Lineamientos para la obtención y comunicación del índice de Calidad del
      Aire y Riesgos a la salud". Los valores que se reportan son los índices
      para el día y hora indicada. El área de influencia de las estaciones de
      monitoreo es de aproximadamente dos kilómetros de radio.
    </p>
  </footer>

</main>
<script
  src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</body>
</html>