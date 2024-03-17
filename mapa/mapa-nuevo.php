<meta http-equiv="refresh" content="3600">
<link href="../pronosticos/css/forms-concentracion.css" rel="stylesheet"/>
<style>
  h2, h5 {
    text-align: center;
    margin: 0 !important;
  }

  h5.tit_fecha {text-align: center}
  p {
    text-align: justify;
  }
</style>

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
<p class='pDatos'>
  Esta información se actualiza cada hora <br>
  Última actualización: <?= $indexReport["Date_Time"] ?> <br>
  Haga clic sobre las celdas de la tabla para ver recomendaciones
</p>
<table style="width:100%; min-width: 250px;">
  <thead>
  <tr>
    <th class="th15">Estación /<br>Contaminante</th>
      <?php
      foreach ($indexReport["Locations"] as $location) : ?>
        <th class="th15">
          <div>
            <a href="http://aire.cemcaq.mx/informacion/" target="_blank">
              <input type="image" class="imgEst"
                   src="<?= $locationImageUrls[$location['Code']] ?>"
                   alt="Logo de la estación <?= $location['Name'] ?>">
            </a>
          </div>
          <div><?= $location["Name"] ?></div>
          <div>(<?= $location["Code"] ?>)</div>
        </th>
      <?php
      endforeach ?>
  </tr>
  </thead>
  <tbody>
  <?php
  foreach ($indexReport["Indexes"] as $index) : ?>
    <tr>
      <td style="text-align: center;">
        <a class="aCont"
           href="<?= $parameterArticleUrls[$index["ParameterCode"]] ?>"
           target="_blank">
          <b><?= $indexReport["Parameters"][$index["ParameterCode"]]["HtmlDisplay"] ?></b></a>
        <p class="pCont"><?= $index["Name"] ?></p>
      </td>
        <?php
        foreach ($indexReport["Results"][$index["Code"]] as $result) : ?>
          <td
            style="width:100%; height:100%; background-color:<?= $indexReport['Categories'][$result['CategoryId']]['ColorHex'] ?>;">
            <div class="divChart"><p class="spanInlineYC">
                <a class="spanInlineYC"
                   style="text-decoration:none"
                   title="Ver recomendaciones"
                   target="_blank"
                   href="<?= $categoryArticleUrls[$result['CategoryId']] ?>">
                    <?= $result["Index"] ?>
                </a>
              </p></div>
          </td>

        <?php
        endforeach ?>
    </tr>

  <?php
  endforeach ?>
  </tbody>
</table>

<table style='width:80%;'>
  <thead>
  <tr class='trLegend'>
    <td class='tdLegend'>
      <div class='legend-green'></div>
    </td>
    <td class='tdLegend'>
      <div class='legend-yellow'></div>
    </td>
    <td class='tdLegend'>
      <div class='legend-orange'></div>
    </td>
    <td class='tdLegend'>
      <div class='legend-red'></div>
    </td>
    <td class='tdLegend'>
      <div class='legend-purple'></div>
    </td>
    <td class='tdLegend'>
      <div class='legend-white'></div>
    </td>
    <td class='tdLegend'>
      <div class='legend-blue'></div>
    </td>
  </tr>
  </thead>
  <tbody>
  <tr class='trLegend'>
    <td class='tdLegend'>
      <strong>BUENA</strong>
    </td>
    <td class='tdLegend'>
      <strong>ACEPTABLE</strong>
    </td>
    <td class='tdLegend'>
      <strong>MALA</strong>
    </td>
    <td class='tdLegend'>
      <strong>MUY MALA</strong>
    </td>
    <td class='tdLegend'>
      <strong>EXTREMADAMENTE MALA</strong>
    </td>
    <td class='tdLegend'>
      <strong>EN MANTENIMIENTO</strong>
    </td>
    <td class='tdLegend'>
      <strong>NO SE MIDE</strong>
    </td>
  </tr>
  </tbody>
</table>
</div>

<p>Notas<br/>Los índices de aire y salud (IAS) y las categorías del mismo,
  incluyendo las <a
    href="http://aire.cemcaq.mx/wp-content/uploads/2021/04/Bandas_IAS_NOM_172_SEMARNAT_2019.jpg"
    target="_blank">BANDAS CROMÁTICAS</a>&nbsp;asociadas a la calidad del aire y
  nivel de riesgo, son las establecidas en la Norma Oficial Mexicana
  NOM-172-SEMARNAT-2019 "Lineamientos para la obtención y comunicación del
  índice de Calidad del Aire y Riesgos a la salud". Los valores que se reportan
  son los índices para el día y hora indicada. El área de influencia de las
  estaciones de monitoreo es de aproximadamente dos kilómetros de radio.</p>
