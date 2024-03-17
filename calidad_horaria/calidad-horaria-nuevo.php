<link href="http://www.cemcaq.mx/pronosticos/css/forms-concentracion.css"
      rel="stylesheet"/>
<link rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">

<style>
  h2 {
    text-align: center;
  }

  p {
    text-align: justify;
  }

  #mapContainer {
    width: 100%;
    height: 600px;
    margin: auto;
    /*float: right;*/
  }

  .imgLeyend {
    width: 30px;
    height: 30px;
    margin: 2px 0px 5px 0px;
  }

  .td-contaminante {
    font-family: Open Sans Regular !important;
    font-size: 14px;
    text-align: center;
    color: #383838;
    font-weight: bold;
    height: 35px !important;
  }

  .container-diagnostico-main {
    background-color: rgba(85, 85, 85, 0.6);
    padding: 1px;
    width: 20%;
    position: absolute;
    z-index: 100;
    float: left;
    /*  top:100px;*/
    display: none;
  }

  .container-diagnostico {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 5px;
    width: 100%;
    top: 20px;
    height: 600px;
    overflow: scroll;
  }

  .cinta-show-hide-legend {
    border-radius: 2px;
    background-color: #003D6E;
    padding: 5px;
    width: 100%;
    height: 38px;
  }

  .cinta-show-hide-legend-img {
    width: 30px;
    height: 20px;
    margin: 2px;
  }

  .cinta-show-hide-span {
    color: white;
  }

  #relativeDiv {
    position: relative;
  }

  .button {
    background-color: #68A7DD;
    border: none;
    color: white;
    padding: 10px 10px;
    text-align: center;
    font-size: 14px;
    cursor: pointer;
    display: none;
  }

  .text {
    color: #003d6e !important;
    color: #666 !important;
    font-size: 1rem !important;
    line-height: 1.5 !important;
    font-family: "Open Sans" !important;
  }

  @media screen and (max-width: 600px) {
    .container-diagnostico-main {
      width: 100%;
      /* margin-top: 0;*/
    }
  }


  .overlay {
    background: rgba(0, 0, 0, .3);
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    align-items: center;
    justify-content: center;
    display: flex;
    visibility: hidden;
  }

  .overlay.active {
    visibility: visible;
  }

  .popup {
    background: #F8F8F8;
    box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.3);
    border-radius: 3px;
    font-family: 'Avenir LT Std', sans-serif;
    padding: 20px;
    text-align: Center;
    width: 600px;
    transform: scale(0.7);
    opacity: 1;
  }

  .popup .btn-cerrar-popup {
    font-size: 16px;
    line-height: 16px;
    display: block;
    text-align: right;
    color: #BBBBBB;
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
  }

  .popup .btn-cerrar-popup:hover {
    color: #000;
  }

</style>

<?php
$json        = file_get_contents('reporte_ica.json');
$indexReport = json_decode($json, true);

// Get max category id per location
foreach ($indexReport["Locations"] as $locationCode => $location) {
    $maxCategoryId = 0;
    foreach ($indexReport["Results"] as $indexCode => $indexResult) {
        if ($indexResult[$locationCode]["CategoryId"] > $maxCategoryId) {
            $maxCategoryId = $indexResult[$locationCode]["CategoryId"];
        }
    }

    $indexReport["Locations"][$locationCode]["MaxCategoryId"] = $maxCategoryId;
}
?>
<h2>INDICE DE AIRE Y SALUD </h2>
<p class="pDatos">
  Esta información se actualiza cada hora <br>
  Última actualización: <?= $indexReport["DateTime"] ?> <br>
</p>


<button id='btnNavigate' name='btnNavigate' class='button'
        onclick='navigateToSJR()'>Ir a San Juan del Río
</button>
<button id='btnNavigateReturn' name='btnNavigateReturn' class='button'
        onclick='navigateToQro()'>Regresar a Qro.
</button>
<div id="relativeDiv">
  <div id="cinta-show-hide-legend"
       class="cinta-show-hide-legend">
                    <span class="cinta-show-hide-span ">
                        &nbsp<img class="cinta-show-hide-legend-img"
                                  id="show-hide-img"
                                  src="http://www.cemcaq.mx/pronosticos/iconos/mapa/hide.png"
                                  alt="img">
                        &nbsp&nbsp
                        Querétaro
                    </span>
  </div>
  <div id="mapContainer"></div>
  <br>
</div>
<!-- termina tabla ---------------------------------------------------------------------------------------------------------------->

<!-- Recomendaciones -->
<h4 style="text-align: center;">
  Recomendaciones a la población para cada categoría del índice de aire y salud
</h4>
<table align=center style='width:100%;'>
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
      <a
        href="http://aire.cemcaq.mx/buena-calidad-del-aire-riesgo-bajo/"><strong>BUENA</strong></a>
    </td>
    <td class='tdLegend'>
      <a
        href="http://aire.cemcaq.mx/recomendaciones/"><strong>ACEPTABLE</strong></a>
    </td>
    <td class='tdLegend'>
      <a
        href="http://aire.cemcaq.mx/mala-calidad-del-aire-riesgo-alto/"><strong>MALA</strong></a>
    </td>
    <td class='tdLegend'>
      <a
        href="http://aire.cemcaq.mx/calidad-del-aire-muy-mala-riesgo-muy-alto/"><strong>MUY
          MALA</strong></a>
    </td>
    <td class='tdLegend'>
      <a
        href="http://aire.cemcaq.mx/extremadamente-mala-calidad-del-aire-riesgo-extremadamente-alto"><strong>EXTREMADAMENTE
          MALA</strong></a>
    </td>
    <td class='tdLegend'>
      <strong>EN MANTENIMIENTO</strong></a>
    </td>
    <td class='tdLegend'>
      <strong>NO SE MIDE</strong></a>
    </td>
  </tr>
  </tbody>
</table>
<br/>
<br/>
<p class="text"><b>Notas</b><br/>a) Los índices de aire y salud, las categorías
  del mismo, el&nbsp;<a
    href="http://aire.cemcaq.mx/wp-content/uploads/2021/04/Bandas_IAS_NOM_172_SEMARNAT_2019.jpg"
    target="_blank">color asociado a cada
    categoría</a>, el nivel de riesgo y la descripción de riesgo son las
  establecidas en la Norma Oficial Mexicana
  NOM-172-SEMARNAT-2019 "Lineamientos para la obtención y comunicación del
  índice de Calidad del Aire y Riesgos a la
  salud", b) El índice de aire y salud tiene propósitos informativos sobre el
  estado de la calidad del aire, los
  riesgos a la salud y las medidas de protección que deberán difundirse a la
  población, c)&nbsp;El índice aire y salud
  sólo tiene fines de información para prevenir a la población en una ciudad o
  localidad en una hora determinada.</p>
<hr/>
<div class="overlay" id="overlay">
  <div class="popup" id="popup">
    <button type="button"
       id="btn-cerrar-popup"
       class="btn-cerrar-popup">
      <i class="fas fa-times"></i>
    </button>
    <div class="popup-data" id="popup-data"></div>
  </div>
</div>


<script
  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnnzmm550Bo1McFJZ_MCaQ5IKha6TH8G8"></script>
<script type="text/javascript">
  const reportICAData = <?= json_encode($indexReport)?>;
  const btnNavigate = document.getElementById('btnNavigate');
  const btnNavigateReturn = document.getElementById('btnNavigateReturn');
  const mapContainerElement = document.getElementById('mapContainer');
  let mapComponent;

  function navigateToSJR() {
    const latLng = new google.maps.LatLng(20.389182, -99.973615);
    mapComponent.panTo(latLng);
    btnNavigate.style.display = 'none';
    btnNavigateReturn.style.display = 'block';
  }

  function navigateToQro() {
    const latLng = new google.maps.LatLng(20.587440, -100.394989);
    mapComponent.panTo(latLng);
    btnNavigate.style.display = 'block';
    btnNavigateReturn.style.display = 'none';
  }

  function initMap() {
    const isMobileScreenSize = screen.width < 1024;

    mapComponent = new google.maps.Map(mapContainerElement, {
      center: {
        lat: isMobileScreenSize ? 20.587440 : 20.524,
        lng: isMobileScreenSize ? -100.394989 : -100.213,
      },
      zoom: 11,
      scrollwheel: true,
    });

    if (isMobileScreenSize) {
      btnNavigate.style.display = 'block';
    }
    const styles = {
      default: null,
      hide: [
        {
          featureType: 'poi',
          stylers: [
            {
              visibility: 'off',
            },
          ],
        },
        {
          featureType: 'administrative',
          elementType: 'labels',
          stylers: [
            {
              visibility: 'off',
            },
          ],
        },
      ],
    };
    mapComponent.setOptions({
      styles: styles.hide,
    });

    const overlayElement = document.getElementById('overlay');
    const btnCerrarPopup = document.getElementById('btn-cerrar-popup');

    btnCerrarPopup.addEventListener('click', function () {
      overlayElement.classList.remove('active');
    });

    const infoTable = document.getElementById('popup-data');

    const markerIcon = {
      url: 'http://aire.cemcaq.mx/pronosticos/iconos/markerIcon.svg',
      scaledSize: new google.maps.Size(40, 40),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(20, 24),
      labelOrigin: new google.maps.Point(26, 24),
    };

    function getPopUpTemplate(location, indexResults) {
      const maxCategory = reportICAData.Categories[location.MaxCategoryId];
      return `<table style="width:100%; text-align: center;">
              <caption>
                <strong>ESTACIÓN: ${location.Name} <br>Índice Aire y Salud</strong>
              </caption>
              <thead>
                <tr>
                  <th colspan="2"
                      style="background-color:${maxCategory.ColorHex};">
                    ${maxCategory.Quality}
                  </th>
                </tr>
                <tr>
                  <th>Contaminante</th>
                  <th>Valor</th>
                </tr>
            </thead>
            <tbody>
              ${indexResults.map(function (indexResult) {
        return getIndexRowTemplate(indexResult[location.Code]);
      }).join('')}
            </tbody>
            </table>`;
    }

    function getIndexRowTemplate(indexResult) {
      const category = reportICAData.Categories[indexResult.CategoryId];
      const parameter = reportICAData.Parameters[indexResult.ParameterCode];
      return `<tr>
                <td class="td-contaminante">
                  <b>${parameter.HtmlDisplay}</b>
                </td>
                <td class="td-contaminante"
                    style="background-color:${category.ColorHex};">
                  <span>${indexResult.Index ?? ""}</span>
                </td>
              </tr>`;
    }

    for (const location of Object.values(reportICAData.Locations)) {
      marker = new google.maps.Marker({
        position: {
          lat: location.Lat,
          lng: location.Lng,
        },
        icon: markerIcon,
        label: {
          text: location.Code,
          color: '#003D6E;',
          fontSize: '18px',
          fontWeight: 'bold',
        },
        map: mapComponent,
        title: location.Code,
      });
      _ = new google.maps.Circle({
        strokeColor: reportICAData.Categories[location.MaxCategoryId].ColorHex,
        strokeOpacity: 1,
        strokeWeight: 2,
        fillColor: reportICAData.Categories[location.MaxCategoryId].ColorHex,
        fillOpacity: 0.5,
        map: mapComponent,
        center: {
          lat: location.Lat,
          lng: location.Lng,
        },
        radius: 1500,
        clickable: false,
        title: location.Code,
      });

      google.maps.event.addListener(marker, 'click', function () {
        console.log("open");
        infoTable.innerHTML = getPopUpTemplate(location, Object.values(reportICAData.Results));
        overlayElement.classList.add('active');
      });
    }
  }

  initMap();
</script>