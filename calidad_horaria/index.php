<?php

$json        = file_get_contents('reporte_ica.json');
$indexReport = json_decode($json, true);

?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
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

  #map {
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
    /*color: #194D86;		*/
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
    /*background-color: #003D6E;*/
    background-color: #68A7DD;
    border: none;
    color: white;
    padding: 10px 10px;
    text-align: center;
    font-size: 14px;
    cursor: pointer;
    display: none;
  }

  .button:hover {
    background-color: green;
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

    transition: .3s ease all;
    transform: scale(0.7);
    opacity: 1;
  }

  .popup .btn-cerrar-popup {
    font-size: 16px;
    line-height: 16px;
    display: block;
    text-align: right;
    transition: .3s ease all;
    color: #BBBBBB;
  }

  .popup .btn-cerrar-popup:hover {
    color: #000;
  }

</style>
<script
  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnnzmm550Bo1McFJZ_MCaQ5IKha6TH8G8&callback=initMap"></script>
<h2> INDICE DE AIRE Y SALUD </h2>
<p>Última actualización: <?= $indexReport["DateTime"] ?></p>

<!-- Recomendaciones -->
<h4 style="text-align: center;">&nbsp;Recomendaciones a la población para cada
  categoría del índice de aire y salud</h4>
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
    <a href="#" id="btn-cerrar-popup" class="btn-cerrar-popup"><i
        class="fas fa-times"></i></a>
    <div class="popup-data" id="popup-data">
    </div>
  </div>
</div>
</body>
</html>


<table style="border: hidden">
  <tr style="border: hidden">
    <th style="border: hidden" WIDTH="75%">

      </script>

      <script type="text/javascript">

        const COLOR_AZUL_CLARO = '#bdd7ee';
        const COLOR_BLANCO = '#fff';
        const COLOR_VERDE = '#00e400';
        const COLOR_AMARILLO = '#ff0';
        const COLOR_NARANJA = '#ff7e00';
        const COLOR_ROJO = '#f00';
        const COLOR_MORADO = '#8f3f97';

        const COLOR_ESTATUS = {
          '#BDD7EE': '',
          '#FFFFFF': '',
          '#00E400': 'BUENA',
          '#FFFF00': 'ACEPTABLE',
          '#FF7E00': 'MALA',
          '#FF0000': 'MUY MALA',
          '#8F3F97': 'EXTREMADAMENTE MALA',
        };

        var map;
        var kmlLayerAlmacenes;
        var kmlLayerBanos;
        var kmlLayerCarp;
        var kmlLayerTerminal;
        var kmlLayerConst;
        var kmlLayerCarton;
        var kmlLayerGas;
        var kmlLayerCopias;
        var kmlLayerGasolina;
        var kmlLayerHojalateria;
        var kmlLayerHospital;
        var kmlLayerHotel;
        var kmlLayerLab;
        var kmlLayerPan;
        var kmlLayerPollo;
        var kmlLayerTImpresion;
        var kmlLayerTPintar;
        var kmlLayerTElectrico;
        var kmlLayerTMecanico;
        var kmlLayerTintoreria;
        var kmlLayerTlapaleria;
        var kmlLayerTortilleria;

        jQuery(document).ready(function () {
          jQuery('input[type="checkbox"]').click(function () {
            if (jQuery(this).is(':checked')) {
              var kmlLayer = (jQuery(this)).prop('value');
              activateMap(kmlLayer);
            } else if (jQuery(this).is(':not(:checked)')) {
              var kmlLayer = (jQuery(this)).prop('value');
              activateMap(kmlLayer);
            }
          });
          jQuery('#show-hide-img').click(function () {
            jQuery('#main-legend').animate({
              width: 'toggle',
              opacity: '0.9',
            }, 'slow');
            /* jQuery('#main-legend').toggle('slow', function() {
                 // Animation complete.
             });*/
            var images = jQuery('#show-hide-img').attr('src');
            if (images == 'http://www.cemcaq.mx/pronosticos/iconos/mapa/hide.png' || images ==
              '.http://www.cemcaq.mx/pronosticos/iconos/mapa/hide.png') {
              jQuery('#show-hide-img').attr('src', '.http://www.cemcaq.mx/pronosticos/iconos/mapa/show.png');
            } else {
              jQuery('#show-hide-img').attr('src', 'http://www.cemcaq.mx/pronosticos/iconos/mapa/hide.png');
            }
            // return false;
          });
        });
        var isFullScreen = 0;

        function onBoundsChanged() {
          if (jQuery(map.getDiv()).children().eq(0).height() == window.innerHeight && jQuery(map.getDiv())
            .children().eq(0).width() == window.innerWidth) {
            // alert('FULL SCREEN' );
            var legendControl = document.getElementById('main-legend');
            map.controls[google.maps.ControlPosition.LEFT_CENTER].push(
              legendControl); //TOP_LEFT, LEFT_TOP, TOP_CENTER
            isFullScreen = 1;
          } else {
            // alert('NOT FULL SCREEN');
            if (isFullScreen == 1) {
              // map.controls[google.maps.ControlPosition.LEFT_CENTER].clear();
              // map.controls[google.maps.ControlPosition.RIGHT_TOP].removeAt(index);
              isFullScreen = 0;
              window.location.href = 'http://www.cemcaq.mx/usuarios/calidad-del-aire-horaria-mapam';
            }
          }
        }

        function navigateToSJR() {
          //alert("ir a sjr");
          var latLng = new google.maps.LatLng(20.389182, -99.973615);
          map.panTo(latLng);
          document.getElementById('btnNavigate').style.display = 'none';
          document.getElementById('btnNavigateReturn').style.display = 'block';
        }

        function navigateToQro() {
          var latLng = new google.maps.LatLng(20.587440, -100.394989);
          map.panTo(latLng);

          document.getElementById('btnNavigate').style.display = 'block';
          document.getElementById('btnNavigateReturn').style.display = 'none';
        }

        function initMap() {

          //alert("La resolución de tu pantalla es: " + screen.width + " x " + screen.height);
          if (screen.width < 1024) {
            map = new google.maps.Map(document.getElementById('map'), { //20.389182, -99.973615
              center: {
                lat: 20.587440,
                lng: -100.394989,
              }, // {lat: 20.524, lng: -100.213}
              zoom: 11, // 9
              scrollwheel: true,
            });
            document.getElementById('btnNavigate').style.display = 'block';
          } else {
            map = new google.maps.Map(document.getElementById('map'), { //20.389182, -99.973615
              center: {
                lat: 20.524,
                lng: -100.213,
              }, //20.587440, -100.394989
              zoom: 11, // 12
              scrollwheel: true,
            });
          }
          // insertar la leyenda en el mapa
          // google.maps.event.addListener( map, 'bounds_changed', onBoundsChanged );

          var styles = {
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
              /*{
          featureType: 'poi.business',
          stylers: [{visibility: 'off'}]
        },
        {
          featureType: 'transit',
          elementType: 'labels.icon',
          stylers: [{visibility: 'off'}]
        },
        {
          featureType: 'road',
          elementType: 'labels.icon',
          stylers: [{visibility: 'off'}]
            },
        {
          featureType: 'road.highway',
          elementType: 'labels',
          stylers: [{visibility: 'off'}]
        },
        {
          featureType: 'road.arterial',
          elementType: 'labels',
          stylers: [{visibility: 'off'}]
        },*/
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
          map.setOptions({
            styles: styles['hide'],
          });

          var colorCEN = markColor(colorsCEN);
          var colorCAP = markColor(colorsCAP);
          var colorCOR = markColor(colorsCOR);
          var colorEMA = markColor(colorsEMA);
          var colorEPG = markColor(colorsEPG);
          var colorFEO = markColor(colorsFEO);
          var colorJOV = markColor(colorsJOV);
          var colorSJR = markColor(colorsSJR);

          var estaciones = [

            ['CAP', 20.610413, -100.438283, estCAP, colorCAP],
            ['COR', 20.552470, -100.445826, estCOR, colorCOR],
            ['EPG', 20.627538, -100.408622, estEPG, colorEPG],
            ['FEO', 20.634935, -100.459333, estFEO, colorFEO],
            ['JOV', 20.565872, -100.389247, estJOV, colorJOV],
            ['SJR', 20.389182, -99.973615, estSJR, colorSJR],
          ];

          var overlay = document.getElementById('overlay'),
            popup = document.getElementById('popup'),
            btnCerrarPopup = document.getElementById('btn-cerrar-popup');

          btnCerrarPopup.addEventListener('click', function (e) {
            e.preventDefault();
            overlay.classList.remove('active');
            popup.classList.remove('active');
          });

          var infoTable = document.getElementById('popup-data');
          var infowindow = new google.maps.InfoWindow;
          var marker, circulo, i;

          //252025.svg
          var markerIcon = {
            // url: 'http://www.cemcaq.mx/pronosticos/iconos/252025.svg',
            url: 'http://aire.cemcaq.mx/pronosticos/iconos/markerIcon.svg',
            scaledSize: new google.maps.Size(40, 40),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(20, 24), // (32 -> horizontal ,45 -> vertical),
            labelOrigin: new google.maps.Point(26,
              24), // 30 -> horizontal ,48 -> vertical posicion de la etiqueta respecto a la marca
          };

          for (i = 0; i < estaciones.length; i++) {
            marker = new google.maps.Marker({
              position: {
                lat: estaciones[i][1],
                lng: estaciones[i][2],
              },
              icon: markerIcon,
              label: {
                text: estaciones[i][0],
                color: '#003D6E;',
                fontSize: '18px',
                fontWeight: 'bold',
              },
              map: map,
              title: estaciones[i][0],
            });
            var circulo = new google.maps.Circle({
              strokeColor: estaciones[i][4],
              strokeOpacity: 1,
              strokeWeight: 2,
              fillColor: estaciones[i][4],
              fillOpacity: 0.5,
              map: map,
              center: {
                lat: estaciones[i][1],
                lng: estaciones[i][2],
              },
              radius: 1500,
              clickable: false,
              title: estaciones[i][0],
            });

            if (screen.width < 1024) {
              google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                  infoData = '<div >' +
                    estaciones[i][3]
                      .replace('\[COLOR\]', estaciones[i][4])
                      .replace('\[ESTATUS\]', COLOR_ESTATUS[estaciones[i][4]]) +
                    '</div>';

                  infoTable.innerHTML = infoData;
                  overlay.classList.add('active');
                  popup.classList.add('active');

                  // infowindow.setContent(infoData);
                  // infowindow.open(map, marker);
                };
              })(marker, i));
            } else {
              google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                  infoData = '<div >' +
                    estaciones[i][3]
                      .replace('\[COLOR\]', estaciones[i][4])
                      .replace('\[ESTATUS\]', COLOR_ESTATUS[estaciones[i][4]]) +
                    '</div>';

                  infoTable.innerHTML = infoData;
                  overlay.classList.add('active');
                  popup.classList.add('active');                                // infowindow.setContent(infoData);
                  // infowindow.open(map, marker);
                };
              })(marker, i));
            }
            google.maps.event.addListener(marker, 'mouseout', (function (marker, i) {
              return function () {
                infowindow.setContent(null);
                infowindow.close();
              };
            })(marker, i));
          }
          // KML LAYER
          var kmlAlmacenes = 'http://aire.cemcaq.mx/pronosticos/kml/Almacen.kmz';
          var kmlBanos = 'http://aire.cemcaq.mx/pronosticos/kml/Banos.kmz';
          var kmlCarp = 'http://aire.cemcaq.mx/pronosticos/kml/Carpinterias.kmz';
          var kmlTerminal = 'http://aire.cemcaq.mx/pronosticos/kml/BTerminal.kmz';
          var kmlConst = 'http://aire.cemcaq.mx/pronosticos/kml/Constructoras_new.kmz';
          var kmlCarton = 'http://aire.cemcaq.mx/pronosticos/kml/Carton.kmz';
          var kmlGas = 'http://aire.cemcaq.mx/pronosticos/kml/Gas.kmz';
          var kmlCopias = 'http://aire.cemcaq.mx/pronosticos/kml/Copias.kmz';
          var kmlGasolina = 'http://aire.cemcaq.mx/pronosticos/kml/Gasolina.kmz';
          var kmlHojalateria = 'http://aire.cemcaq.mx/pronosticos/kml/Hojalateria.kmz';
          var kmlHospital = 'http://aire.cemcaq.mx/pronosticos/kml/Hospital.kmz';
          var kmlHotel = 'http://aire.cemcaq.mx/pronosticos/kml/Hoteles.kmz';
          var kmlLab = 'http://aire.cemcaq.mx/pronosticos/kml/Laboratorio.kmz';
          var kmlPan = 'http://aire.cemcaq.mx/pronosticos/kml/Pan.kmz';
          var kmlPollo = 'http://aire.cemcaq.mx/pronosticos/kml/Pollo.kmz';
          var kmlTImpresion = 'http://aire.cemcaq.mx/pronosticos/kml/TImpresion.kmz';
          var kmlTPintar = 'http://aire.cemcaq.mx/pronosticos/kml/TPintar.kmz';
          var kmlTElectrico = 'http://aire.cemcaq.mx/pronosticos/kml/TElectrico.kmz';
          var kmlTMecanico = 'http://aire.cemcaq.mx/pronosticos/kml/TMecanico.kmz';
          var kmlTintoreria = 'http://aire.cemcaq.mx/pronosticos/kml/Tintoreria.kmz';
          var kmlTlapaleria = 'http://aire.cemcaq.mx/pronosticos/kml/Tlapaleria.kmz';
          var kmlTortilleria = 'http://aire.cemcaq.mx/pronosticos/kml/Tortilleria.kmz';

          kmlLayerAlmacenes = new google.maps.KmlLayer(kmlAlmacenes, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
            map: map,
          });
          if (kmlLayerAlmacenes.getMap() !== null) {
            kmlLayerAlmacenes.setMap(null);
          }

          kmlLayerBanos = new google.maps.KmlLayer(kmlBanos, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
            map: map,
          });
          if (kmlLayerBanos.getMap() !== null) {
            kmlLayerBanos.setMap(null);
          }

          kmlLayerCarp = new google.maps.KmlLayer(kmlCarp, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
            map: map,
          });
          if (kmlLayerCarp.getMap() !== null) {
            kmlLayerCarp.setMap(null);
          }

          kmlLayerTerminal = new google.maps.KmlLayer(kmlTerminal, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerTerminal.getMap() !== null) {
            kmlLayerTerminal.setMap(null);
          }

          kmlLayerConst = new google.maps.KmlLayer(kmlConst, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerConst.getMap() !== null) {
            kmlLayerConst.setMap(null);
          }

          kmlLayerCarton = new google.maps.KmlLayer(kmlCarton, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerCarton.getMap() !== null) {
            kmlLayerCarton.setMap(null);
          }

          kmlLayerGas = new google.maps.KmlLayer(kmlGas, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerGas.getMap() !== null) {
            kmlLayerGas.setMap(null);
          }

          kmlLayerCopias = new google.maps.KmlLayer(kmlCopias, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerCopias.getMap() !== null) {
            kmlLayerCopias.setMap(null);
          }

          kmlLayerGasolina = new google.maps.KmlLayer(kmlGasolina, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerGasolina.getMap() !== null) {
            kmlLayerGasolina.setMap(null);
          }

          kmlLayerHojalateria = new google.maps.KmlLayer(kmlHojalateria, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerHojalateria.getMap() !== null) {
            kmlLayerHojalateria.setMap(null);
          }

          kmlLayerHospital = new google.maps.KmlLayer(kmlHospital, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerHospital.getMap() !== null) {
            kmlLayerHospital.setMap(null);
          }

          kmlLayerHotel = new google.maps.KmlLayer(kmlHotel, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerHotel.getMap() !== null) {
            kmlLayerHotel.setMap(null);
          }

          kmlLayerLab = new google.maps.KmlLayer(kmlLab, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerLab.getMap() !== null) {
            kmlLayerLab.setMap(null);
          }

          kmlLayerPan = new google.maps.KmlLayer(kmlPan, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerPan.getMap() !== null) {
            kmlLayerPan.setMap(null);
          }

          kmlLayerPollo = new google.maps.KmlLayer(kmlPollo, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerPollo.getMap() !== null) {
            kmlLayerPollo.setMap(null);
          }

          kmlLayerTImpresion = new google.maps.KmlLayer(kmlTImpresion, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerTImpresion.getMap() !== null) {
            kmlLayerTImpresion.setMap(null);
          }

          kmlLayerTPintar = new google.maps.KmlLayer(kmlTPintar, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerTPintar.getMap() !== null) {
            kmlLayerTPintar.setMap(null);
          }

          kmlLayerTElectrico = new google.maps.KmlLayer(kmlTElectrico, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerTElectrico.getMap() !== null) {
            kmlLayerTElectrico.setMap(null);
          }

          kmlLayerTMecanico = new google.maps.KmlLayer(kmlTMecanico, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerTMecanico.getMap() !== null) {
            kmlLayerTMecanico.setMap(null);
          }

          kmlLayerTintoreria = new google.maps.KmlLayer(kmlTintoreria, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerTintoreria.getMap() !== null) {
            kmlLayerTintoreria.setMap(null);
          }

          kmlLayerTlapaleria = new google.maps.KmlLayer(kmlTlapaleria, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerTlapaleria.getMap() !== null) {
            kmlLayerTlapaleria.setMap(null);
          }

          kmlLayerTortilleria = new google.maps.KmlLayer(kmlTortilleria, {
            suppressInfoWindows: false, // show infoWindowHtml
            preserveViewport: true,
          });
          if (kmlLayerTortilleria.getMap() !== null) {
            kmlLayerTortilleria.setMap(null);
          }

        }

        function markColor(colors) {

          try {
            var colorName, auxColors = [];
            for (i = 0; i < colors.length; i++) {
              var color = getColorNumber(colors[i]);
              auxColors.push(color);
            }
            var i, max = 0;
            for (i = 0; i < auxColors.length; i++) {
              if (auxColors[i] > max) {
                max = auxColors[i];
              }
            }
            colorName = getColorName(max);
          } catch (err) {
            alert('Entro los colores - ' + err.message);
            //document.getElementById("demo").innerHTML = err.message;
          }

          return colorName;
        }

        function getColorNumber(colorName) {
          var colorNumber;
          if (colorName == '#bdd7ee') {
            colorNumber = 0; // BLUE LIGH
          } else if (colorName == '#fff') {
            colorNumber = 1; // BLANCO
          } else if (colorName == '#00e400') {
            colorNumber = 2; // VERDE
          } else if (colorName == '#ff0') {
            colorNumber = 3; // AMARILLO
          } else if (colorName == '#ff7e00') {
            colorNumber = 4; // NARANJA
          } else if (colorName == '#f00') {
            colorNumber = 5; // ROJO
          } else if (colorName == '#8f3f97') {
            colorNumber = 6; // MORADO
          }
          return colorNumber;
        }

        function getColorName(colorNumber) {
          var colorName;
          if (colorNumber == 0) {
            colorName = '#bdd7ee'; // BLUE LIGH
          } else if (colorNumber == 1) {
            colorName = '#fff'; // BLANCO
          } else if (colorNumber == 2) {
            colorName = '#00e400'; // VERDE
          } else if (colorNumber == 3) {
            colorName = '#ff0'; // AMARILLO
          } else if (colorNumber == 4) {
            colorName = '#ff7e00'; // NARANJA
          } else if (colorNumber == 5) {
            colorName = '#f00'; // ROJO
          } else if (colorNumber == 6) {
            colorName = '#8f3f97'; // MORADO
          }
          return colorName;
        }

        function getUrlArticle($colorNumber) {
          $urlArticle = 0;
          if ($colorNumber == 0) {
            $urlArticle = ''; // BLUE LIGH
          } else if ($colorNumber == 1) {
            $urlArticle = ''; // BLANCO
          } else if ($colorNumber == 2) {
            $urlArticle = 'http://aire.cemcaq.mx/buena-calidad-del-aire-riesgo-bajo/'; // VERDE
          } else if ($colorNumber == 3) {
            $urlArticle = 'http://aire.cemcaq.mx/recomendaciones/'; // AMARILLO
          } else if ($colorNumber == 4) {
            $urlArticle = 'http://aire.cemcaq.mx/mala-calidad-del-aire-riesgo-alto/'; // NARANJA
          } else if ($colorNumber == 5) {
            $urlArticle = 'http://aire.cemcaq.mx/calidad-del-aire-muy-mala-riesgo-muy-alto/'; // ROJO
          } else if ($colorNumber == 6) {
            $urlArticle = 'http://aire.cemcaq.mx/extremadamente-mala-calidad-del-aire-riesgo-extremadamente-alto/'; // MORADO
          }
          return $urlArticle;
        }

        function activateMap(kmlLayer) {
          switch (kmlLayer) {
            case 'kmlLayerAlmacenes':
              if (kmlLayerAlmacenes.getMap() === null) {
                kmlLayerAlmacenes.setMap(map);
              } else if (kmlLayerAlmacenes.getMap() !== null) {
                kmlLayerAlmacenes.setMap(null);
              }
              break;
            case 'kmlLayerBanos':
              if (kmlLayerBanos.getMap() === null) {
                kmlLayerBanos.setMap(map);
              } else if (kmlLayerBanos.getMap() !== null) {
                kmlLayerBanos.setMap(null);
              }
              break;
            case 'kmlLayerCarp':
              if (kmlLayerCarp.getMap() === null) {
                kmlLayerCarp.setMap(map);
              } else if (kmlLayerCarp.getMap() !== null) {
                kmlLayerCarp.setMap(null);
              }
              break;
            case 'kmlLayerTerminal':
              if (kmlLayerTerminal.getMap() === null) {
                kmlLayerTerminal.setMap(map);
              } else if (kmlLayerTerminal.getMap() !== null) {
                kmlLayerTerminal.setMap(null);
              }
              break;
            case 'kmlLayerConst':
              if (kmlLayerConst.getMap() === null) {
                kmlLayerConst.setMap(map);
              } else if (kmlLayerConst.getMap() !== null) {
                kmlLayerConst.setMap(null);
              }
              break;
            case 'kmlLayerCarton':
              if (kmlLayerCarton.getMap() === null) {
                kmlLayerCarton.setMap(map);
              } else if (kmlLayerCarton.getMap() !== null) {
                kmlLayerCarton.setMap(null);
              }
              break;
            case 'kmlLayerGas':
              if (kmlLayerGas.getMap() === null) {
                kmlLayerGas.setMap(map);
              } else if (kmlLayerGas.getMap() !== null) {
                kmlLayerGas.setMap(null);
              }
              break;
            case 'kmlLayerCopias':
              if (kmlLayerCopias.getMap() === null) {
                kmlLayerCopias.setMap(map);
              } else if (kmlLayerCopias.getMap() !== null) {
                kmlLayerCopias.setMap(null);
              }
              break;
            case 'kmlLayerGasolina':
              if (kmlLayerGasolina.getMap() === null) {
                kmlLayerGasolina.setMap(map);
              } else if (kmlLayerGasolina.getMap() !== null) {
                kmlLayerGasolina.setMap(null);
              }
              break;
            case 'kmlLayerHojalateria':
              if (kmlLayerHojalateria.getMap() === null) {
                kmlLayerHojalateria.setMap(map);
              } else if (kmlLayerHojalateria.getMap() !== null) {
                kmlLayerHojalateria.setMap(null);
              }
              break;
            case 'kmlLayerHospital':
              if (kmlLayerHospital.getMap() === null) {
                kmlLayerHospital.setMap(map);
              } else if (kmlLayerHospital.getMap() !== null) {
                kmlLayerHospital.setMap(null);
              }
              break;
            case 'kmlLayerHotel':
              if (kmlLayerHotel.getMap() === null) {
                kmlLayerHotel.setMap(map);
              } else if (kmlLayerHotel.getMap() !== null) {
                kmlLayerHotel.setMap(null);
              }
              break;
            case 'kmlLayerLab':
              if (kmlLayerLab.getMap() === null) {
                kmlLayerLab.setMap(map);
              } else if (kmlLayerLab.getMap() !== null) {
                kmlLayerLab.setMap(null);
              }
              break;
            case 'kmlLayerPan':
              if (kmlLayerPan.getMap() === null) {
                kmlLayerPan.setMap(map);
              } else if (kmlLayerPan.getMap() !== null) {
                kmlLayerPan.setMap(null);
              }
              break;
            case 'kmlLayerPollo':
              if (kmlLayerPollo.getMap() === null) {
                kmlLayerPollo.setMap(map);
              } else if (kmlLayerPollo.getMap() !== null) {
                kmlLayerPollo.setMap(null);
              }
              break;
            case 'kmlLayerTImpresion':
              if (kmlLayerTImpresion.getMap() === null) {
                kmlLayerTImpresion.setMap(map);
              } else if (kmlLayerTImpresion.getMap() !== null) {
                kmlLayerTImpresion.setMap(null);
              }
              break;
            case 'kmlLayerTPintar':
              if (kmlLayerTPintar.getMap() === null) {
                kmlLayerTPintar.setMap(map);
              } else if (kmlLayerTPintar.getMap() !== null) {
                kmlLayerTPintar.setMap(null);
              }
              break;
            case 'kmlLayerTElectrico':
              if (kmlLayerTElectrico.getMap() === null) {
                kmlLayerTElectrico.setMap(map);
              } else if (kmlLayerTElectrico.getMap() !== null) {
                kmlLayerTElectrico.setMap(null);
              }
              break;
            case 'kmlLayerTMecanico':
              if (kmlLayerTMecanico.getMap() === null) {
                kmlLayerTMecanico.setMap(map);
              } else if (kmlLayerTMecanico.getMap() !== null) {
                kmlLayerTMecanico.setMap(null);
              }
              break;
            case 'kmlLayerTintoreria':
              if (kmlLayerTintoreria.getMap() === null) {
                kmlLayerTintoreria.setMap(map);
              } else if (kmlLayerTintoreria.getMap() !== null) {
                kmlLayerTintoreria.setMap(null);
              }
              break;
            case 'kmlLayerTlapaleria':
              if (kmlLayerTlapaleria.getMap() === null) {
                kmlLayerTlapaleria.setMap(map);
              } else if (kmlLayerTlapaleria.getMap() !== null) {
                kmlLayerTlapaleria.setMap(null);
              }
              break;
            case 'kmlLayerTortilleria':
              if (kmlLayerTortilleria.getMap() === null) {
                kmlLayerTortilleria.setMap(map);
              } else if (kmlLayerTortilleria.getMap() !== null) {
                kmlLayerTortilleria.setMap(null);
              }
              break;
          }
        }
      </script>


        <?php

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $directorio
            = 'http://www.cemcaq.mx/images/Reporte_calidad_aire/Reporte_horario/tablamapa.csv';  //----------------------------------------------------------------------------------------------------------------archivo origen

        try {
            $pathFile = $directorio;
            // LEER EL ARCHIVO
            // EL ARCHIVO TIENE CONTENIDO
            if (true) {
                $arqivo = str_replace('\\', '', $pathFile);
                $fp     = fopen($arqivo, "r");
                if ($fp) {
                    //Recorremos el archivo
                    $FechaAux = "";
                    $auxCount = 0;
                    $estCEN   = "";
                    $estCAP   = "";
                    $estCOR   = "";
                    $estEMA   = "";
                    $estEPG   = "";
                    $estFEO   = "";
                    $estJOV   = "";
                    $estSJR   = "";

                    $url0 = "";
                    $url1 = "";
                    $url2 = "";
                    $url3 = "";
                    $url4 = "";
                    $url5 = "";
                    $url6 = "";
                    $url7 = "";

                    $colorsCEN = [];
                    $colorsCAP = [];
                    $colorsCOR = [];
                    $colorsEMA = [];
                    $colorsEPG = [];
                    $colorsFEO = [];
                    $colorsJOV = [];
                    $colorsSJR = [];

                    while (($datos = fgetcsv($fp, ",")) == true) {
                        //print_r($datos);
                        $val0  = "";
                        $col0  = "";
                        $val1  = "";
                        $vals1 = "";
                        $col1  = "";
                        $val2  = "";
                        $vals2 = "";
                        $col2  = "";
                        $val3  = "";
                        $vals3 = "";
                        $col3  = "";
                        $val4  = "";
                        $vals4 = "";
                        $col4  = "";
                        $val5  = "";
                        $vals5 = "";
                        $col5  = "";
                        $val6  = "";
                        $vals6 = "";
                        $col6  = "";
                        $val7  = "";
                        $vals7 = "";
                        $col7  = "";
                        $val8  = "";
                        $vals8 = "";
                        $col8  = "";

                        $numCols = count($datos);
                        if ($numCols >= 9) {
                            $Conts = explode("@", $datos[0]);
                            $val0  = $Conts[0]; //$datos[0];
                            $val1  = $datos[1];
                            $val2  = $datos[2];
                            $val3  = $datos[3];
                            $val4  = $datos[4];
                            $val5  = $datos[5];
                            $val6  = $datos[6];
                            $val7  = $datos[7];
                            $val8  = $datos[8];

                            if ($auxCount == 0) {
                                $hder = explode("@", $datos[1]);
                                $est1 = $hder[0];

                                $hder = explode("@", $datos[2]);
                                $est2 = $hder[0];

                                $hder = explode("@", $datos[3]);
                                $est3 = $hder[0];

                                $hder = explode("@", $datos[4]);
                                $est4 = $hder[0];

                                $hder = explode("@", $datos[5]);
                                $est5 = $hder[0];

                                $hder = explode("@", $datos[6]);
                                $est6 = $hder[0];

                                $hder = explode("@", $datos[7]);
                                $est7 = $hder[0];

                                $hder = explode("@", $datos[8]);
                                $est8 = $hder[0];

                                /* $header = "
                                <thead>
                                    <tr>
                                       <th>Contaminante</th>
                                       <th><div>Valor</div></th>

                                                                    </tr>
                                </thead>
                                    <tbody>"; */

                                $header = "
							<thead>
							    <tr>
								   <th colspan='2' style='background-color:[COLOR];' align='center'>[ESTATUS]</th>
								</tr>
								<tr>
                                   <th>Contaminante</th>
								   <th><div>Valor</div></th>
								    
                                </tr>
							</thead>
							<tbody>";

                                $ias = "Índice Aire y Salud";

                                $estCEN = "<table align=center style='width:100%;'>
								<caption><strong> ESTACI&Oacute;N: $est1 <br>$ias</strong></caption>"
                                          .$header;
                                $estCAP = "<table align=center style='width:100%;'>
								<caption><strong> ESTACI&Oacute;N: $est2 <br>$ias</strong></caption>"
                                          .$header;
                                $estCOR = "<table align=center style='width:100%;'>
								<caption><strong>ESTACI&Oacute;N: $est3 <br>$ias</strong></caption>"
                                          .$header;
                                $estEMA = "<table align=center style='width:100%;'>
								<caption><strong>ESTACI&Oacute;N: $est4 <br>$ias</strong></caption>"
                                          .$header;

                                $estEPG = "<table align=center style='width:100%;'>
                                <caption><strong>ESTACI&Oacute;N: $est5 <br>$ias</strong></caption>"
                                          .$header;
                                $estFEO = "<table align=center style='width:100%;'>
								<caption><strong>ESTACI&Oacute;N: $est6 <br>$ias</strong></caption>"
                                          .$header;

                                $estJOV = "<table align=center style='width:100%;'>
                                <caption><strong>ESTACI&Oacute;N: $est7 <br>$ias</strong></caption>"
                                          .$header;

                                $estSJR = "<table align=center style='width:100%;'>
								<caption><strong>ESTACI&Oacute;N: $est8 <br>$ias</strong></caption>"
                                          .$header;
                            } else {
                                $vals1 = explode("@", $datos[1]);
                                // print_r($vals1);
                                if ($auxCount == 1) {
                                    if (sizeof($vals1) > 1) {
                                        $p    = str_replace("/", "-",
                                            $vals1[0]);
                                        $FechaAux
                                              = "&Uacute;ltima actualizaci&oacute;n "
                                                .date('d/m/Y H:i',
                                                strtotime($p));
                                        $val1 = $vals1[1];
                                        $col1 = $vals1[2];
                                    } else {
                                        $FechaAux = "";
                                        $val1     = $vals1[0];
                                    }
                                } else {
                                    $val1 = $vals1[0];
                                    $col1 = $vals1[1];
                                }

                                //$vals1 = explode ("@", $datos[1]);
                                //$val1 = $vals1[0];
                                //$col1 = $vals1[1];
                                //-----------------------------------------------------informacion para la ventana emergente para cada estación---------------------------
                                $estCEN .= "<tr>                                
								<td class='td-contaminante'>  <b>$val0  </b></td>
								<td class='td-contaminante' style='background-color:$col1;'>                                                                  
									<span>$val1</span>	                             
								</td>
								</tr>";
                                array_push($colorsCEN, $col1);

                                $vals2 = explode("@", $datos[2]);
                                $val2
                                       = $vals2[0];// ($vals2[0] == "NULL" || $vals2[0] == "SM") ? "ND" : $vals2[0];
                                $col2  = $vals2[1];

                                $estCAP .= "<tr>
								<td class='td-contaminante'>  <b>$val0</b>  </td>
								<td class='td-contaminante' style='background-color:$col2;' >
								   <span>$val2</span>
								 </td>                               
								</tr>";
                                array_push($colorsCAP, $col2);

                                $vals3 = explode("@", $datos[3]);
                                $val3
                                       = $vals3[0];// ($vals3[0] == "NULL" || $vals3[0] == "SM") ? "ND" : $vals3[0];
                                $col3  = $vals3[1];

                                $estCOR .= "<tr>
									<td class='td-contaminante'> <b>$val0</b> </td>
									<td class='td-contaminante' style='background-color:$col3;' >
										<span>$val3</span>
									</td>
								</tr>";
                                array_push($colorsCOR, $col3);

                                $vals4  = explode("@", $datos[4]);
                                $val4
                                        = $vals4[0];// ($vals4[0] == "NULL" || $vals4[0] == "SM") ? "ND" : $vals4[0];
                                $col4   = $vals4[1];
                                $estEMA .= "<tr>
									<td class='td-contaminante'> <b>$val0</b> </td>
									<td class='td-contaminante' style='background-color:$col4;' >
										<span>$val4</span>
									</td>
								</tr>";
                                array_push($colorsEMA, $col4);

                                $vals5  = explode("@", $datos[5]);
                                $val5
                                        = $vals5[0];// ($vals5[0] == "NULL" || $vals5[0] == "SM") ? "ND" : $vals5[0];
                                $col5   = $vals5[1];
                                $estEPG .= "<tr>
									<td class='td-contaminante'>  <b>$val0</b>  </td>
									<td class='td-contaminante' style='background-color:$col5;' >
										<span>$val5</span>
									</td>
								</tr>";
                                array_push($colorsEPG, $col5);

                                $vals6  = explode("@", $datos[6]);
                                $val6
                                        = $vals6[0];// ($vals6[0] == "NULL" || $vals6[0] == "SM") ? "ND" : $vals6[0];
                                $col6   = $vals6[1];
                                $estFEO .= "<tr>
                                    <td class='td-contaminante'>  <b>$val0</b>  </td>
                                    <td class='td-contaminante' style='background-color:$col6;' >
                                        <span>$val6</span>
                                    </td>
                                </tr>";
                                array_push($colorsFEO, $col6);

                                $vals7  = explode("@", $datos[7]);
                                $val7
                                        = $vals7[0];// ($vals7[0] == "NULL" || $vals7[0] == "SM") ? "ND" : $vals7[0];
                                $col7   = $vals7[1];
                                $estJOV .= "<tr>
                                    <td class='td-contaminante'>  <b>$val0</b>  </td>
                                    <td class='td-contaminante' style='background-color:$col7;' >
                                        <span>$val7</span>
                                    </td>
                                </tr>";
                                array_push($colorsJOV, $col7);

                                $vals8  = explode("@", $datos[8]);
                                $val8
                                        = $vals8[0];// ($vals8[0] == "NULL" || $vals8[0] == "SM") ? "ND" : $vals8[0];
                                $col8   = $vals8[1];
                                $estSJR .= "<tr>
									<td class='td-contaminante'> <b>$val0</b> </td>
									<td class='td-contaminante' style='background-color:$col8;' >
										<span>$val8</span>
									</td>
                                 </tr>";
                                array_push($colorsSJR, $col8);
                            }
                        }
                        $auxCount = $auxCount + 1;
                    }

                    $estCEN .= "</tbody></table>";
                    $estCAP .= "</tbody></table>";
                    $estCOR .= "</tbody></table>";
                    $estEMA .= "</tbody></table>";
                    $estEPG .= "</tbody></table>";
                    $estFEO .= "</tbody></table>";
                    $estJOV .= "</tbody></table>";
                    $estSJR .= "</tbody></table>";

                    // ESCRIBIR LA FECHA
                    date_default_timezone_set('America/Mexico_City');
                    $cadena     = strftime("%A, %B %d, %Y",
                        strtotime(date("Y-m-d")));
                    $horaActual = date("H");

                    $dayvalue = null;
                    $dayvalue = (strpos($cadena, 'Monday') !== false)
                        ? str_ireplace('Monday', 'Lunes', $cadena) : $dayvalue;
                    $dayvalue = (strpos($cadena, 'Tuesday') !== false)
                        ? str_ireplace('Tuesday', 'Martes', $cadena)
                        : $dayvalue;
                    $dayvalue = (strpos($cadena, 'Wednesday') !== false)
                        ? str_ireplace('Wednesday', 'Mi&eacute;rcoles', $cadena)
                        : $dayvalue;
                    $dayvalue = (strpos($cadena, 'Thursday') !== false)
                        ? str_ireplace('Thursday', 'Jueves', $cadena)
                        : $dayvalue;
                    $dayvalue = (strpos($cadena, 'Friday') !== false)
                        ? str_ireplace('Friday', 'Viernes', $cadena)
                        : $dayvalue;
                    $dayvalue = (strpos($cadena, 'Saturday') !== false)
                        ? str_ireplace('Saturday', 'S&aacute;bado', $cadena)
                        : $dayvalue;
                    $dayvalue = (strpos($cadena, 'Sunday') !== false)
                        ? str_ireplace('Sunday', 'Domingo', $cadena)
                        : $dayvalue;

                    $newdate = null;
                    $newdate = (strpos($dayvalue, 'January') !== false)
                        ? str_ireplace('January', 'enero', $dayvalue)
                        : $newdate;
                    $newdate = (strpos($dayvalue, 'February') !== false)
                        ? str_ireplace('February', 'febrero', $dayvalue)
                        : $newdate;
                    $newdate = (strpos($dayvalue, 'March') !== false)
                        ? str_ireplace('March', 'marzo', $dayvalue) : $newdate;
                    $newdate = (strpos($dayvalue, 'April') !== false)
                        ? str_ireplace('April', 'abril', $dayvalue) : $newdate;
                    $newdate = (strpos($dayvalue, 'May') !== false)
                        ? str_ireplace('May', 'mayo', $dayvalue) : $newdate;
                    $newdate = (strpos($dayvalue, 'June') !== false)
                        ? str_ireplace('June', 'junio', $dayvalue) : $newdate;
                    $newdate = (strpos($dayvalue, 'July') !== false)
                        ? str_ireplace('July', 'julio', $dayvalue) : $newdate;
                    $newdate = (strpos($dayvalue, 'August') !== false)
                        ? str_ireplace('August', 'agosto', $dayvalue)
                        : $newdate;
                    $newdate = (strpos($dayvalue, 'September') !== false)
                        ? str_ireplace('September', 'septiembre', $dayvalue)
                        : $newdate;
                    $newdate = (strpos($dayvalue, 'October') !== false)
                        ? str_ireplace('October', 'octubre', $dayvalue)
                        : $newdate;
                    $newdate = (strpos($dayvalue, 'November') !== false)
                        ? str_ireplace('November', 'noviembre', $dayvalue)
                        : $newdate;
                    $newdate = (strpos($dayvalue, 'December') !== false)
                        ? str_ireplace('December', 'diciembre', $dayvalue)
                        : $newdate;

                    echo "<p>Fecha &nbsp;&nbsp;&nbsp;&nbsp;".$newdate
                         ."&nbsp;&nbsp;&nbsp;&nbsp;".$horaActual.":00 hrs.</br>
                                      ".$FechaAux."</p>";

                    //ESCRIBIR LAA VARIABLES JS => POPUP DE LA MARCA EN EL MAPA
                    echo "<script>\n";
                    echo 'var estCEN = '.json_encode($estCEN).';';
                    echo 'var estCAP = '.json_encode($estCAP).';';
                    echo 'var estCOR = '.json_encode($estCOR).';';
                    echo 'var estEMA = '.json_encode($estEMA).';';
                    echo 'var estEPG = '.json_encode($estEPG).';';
                    echo 'var estFEO = '.json_encode($estFEO).';';
                    echo 'var estJOV = '.json_encode($estJOV).';';
                    echo 'var estSJR = '.json_encode($estSJR).';';

                    echo 'var colorsCEN = '.json_encode($colorsCEN).';';
                    echo 'var colorsCAP = '.json_encode($colorsCAP).';';
                    echo 'var colorsCOR = '.json_encode($colorsCOR).';';
                    echo 'var colorsEMA = '.json_encode($colorsEMA).';';
                    echo 'var colorsEPG = '.json_encode($colorsEPG).';';
                    echo 'var colorsFEO = '.json_encode($colorsFEO).';';
                    echo 'var colorsJOV = '.json_encode($colorsJOV).';';
                    echo 'var colorsSJR = '.json_encode($colorsSJR).';';
                    echo "\n</script>";

                    //echo " Estacion : " . $estEMA;
                    //print_r($colorsAJO);
                    echo "<button id='btnNavigate' name='btnNavigate' class='button' onclick='navigateToSJR()'>Ir a San Juan del Río</button>";
                    echo "<button id='btnNavigateReturn' name='btnNavigateReturn' class='button' onclick='navigateToQro()'>Regresar a Qro.</button>";
                }
                //Cerramos el archivo
                fclose($fp);
            } else {
                echo "El archivo de concentraciones horario esta vacio";
            }
        } catch (Exception $e) {
            echo 'Excepción al leer el archivo de datos: ', $e->getMessage(), "\n";
        }

        ?>
      <div id="relativeDiv">
        <div id="cinta-show-hide-legend" name="cinta-show-hide-legend"
             class="cinta-show-hide-legend">
                    <span class="cinta-show-hide-span ">
                        &nbsp<img class="cinta-show-hide-legend-img"
                                  id="show-hide-img" name="show-hide-img"
                                  src="http://www.cemcaq.mx/pronosticos/iconos/mapa/hide.png"
                                  alt="img">
                        &nbsp&nbsp
                        Querétaro
                    </span>
        </div>
        <div id="main-legend" name="main-legend"
             class="container-diagnostico-main">
          <div class="container-diagnostico">
            <h5> Inventario de fuentes fijas </h5>
            <input type="checkbox" name="ckbkmlLayerAlmacenes"
                   value="kmlLayerAlmacenes"> Almacenes <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/combustibles.png"
                                          alt="Almacenes"><br>
            <input type="checkbox" name="ckbkmlLayerBanos"
                   value="kmlLayerBanos"> Baños<br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/banios.png"
                                          alt="Baños"><br>
            <input type="checkbox" name="ckbkmlLayerCarp" value="kmlLayerCarp">
            Carpinterias<br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/carpinteria.png"
                                          alt="Carpinteria"><br>
            <input type="checkbox" name="ckbkmlLayerTerminal"
                   value="kmlLayerTerminal"> Terminal de
            autobuses<br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/terminal.png"
                                          alt="Carpinteria"><br>
            <input type="checkbox" name="ckbkmlLayerConst"
                   value="kmlLayerConst"> Constructoras<br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/construction.png"
                                          alt="Constructoras"><br>
            <input type="checkbox" name="ckbkmlLayerCarton"
                   value="kmlLayerCarton"> Deposito de carton <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/deposito_carton.png"
                                          alt="Deposito carton"><br>
            <input type="checkbox" name="ckbkmlLayerGas" value="kmlLayerGas">
            Gaseras<br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/Gasera.png"
                                          alt="Gasera"><br>
            <input type="checkbox" name="ckbkmlLayerCopias"
                   value="kmlLayerCopias"> Fotocopiadoras <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/printer.png"
                                          alt="Fotocopiadoras"><br>
            <input type="checkbox" name="ckbkmlLayerGasolina"
                   value="kmlLayerGasolina"> Gasolineras <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/gasolina.png"
                                          alt="Gasolinerias"><br>
            <input type="checkbox" name="ckbkmlLayerHojalateria"
                   value="kmlLayerHojalateria">
            Hojalater&iacute;as <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/hojalateria.png"
                                          alt="Hojalaterias"><br>
            <input type="checkbox" name="ckbkmlLayerHospital"
                   value="kmlLayerHospital"> Hospitales <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/hospital.png"
                                          alt="Hospitales"><br>
            <input type="checkbox" name="ckbkmlLayerHotel"
                   value="kmlLayerHotel"> Hoteles <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/Hotel.png"
                                          alt="Hoteles"><br>
            <input type="checkbox" name="ckbkmlLayerLab" value="kmlLayerLab">
            Laboratorios <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/laboratorio.png"
                                          alt="Laboratorios"><br>
            <input type="checkbox" name="ckbkmlLayerPan" value="kmlLayerPan">
            Panaderias <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/pan.png"
                                          alt="Panaderias"><br>
            <input type="checkbox" name="ckbkmlLayerPollo"
                   value="kmlLayerPollo"> Rosticerias <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/pollo.png"
                                          alt="Rosticerias"><br>
            <input type="checkbox" name="ckbkmlLayerTImpresion"
                   value="kmlLayerTImpresion"> Talleres de
            impresion <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/taller_impresion.png"
                                          alt="Taller de impresion"><br>
            <input type="checkbox" name="ckbkmlLayerTPintar"
                   value="kmlLayerTPintar"> Talleres de pintura
            <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/hojalateria.png"
                                          alt="Taller de pintura"><br>
            <input type="checkbox" name="ckbkmlLayerTElectrico"
                   value="kmlLayerTElectrico"> Talleres
            electricos <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/taller_electrico.png"
                                          alt="Taller electrico"><br>
            <input type="checkbox" name="ckbkmlLayerTMecanico"
                   value="kmlLayerTMecanico"> Taller mecanico
            <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/taller_mecanico.png"
                                          alt="Taller mecanico"><br>
            <input type="checkbox" name="ckbkmlLayerTintoreria"
                   value="kmlLayerTintoreria"> Tintoreria <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/tintoreria.png"
                                          alt="Tintoreria"><br>
            <input type="checkbox" name="ckbkmlLayerTlapaleria"
                   value="kmlLayerTlapaleria"> Tlapalerias<br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/tlapaleria.png"
                                          alt="Tlapaleria"><br>
            <input type="checkbox" name="ckbkmlLayerTortilleria"
                   value="kmlLayerTortilleria"> Tortillerias
            <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp<img class="imgLeyend"
                                          src="http://www.cemcaq.mx/pronosticos/iconos/mapa/tortilleria.png"
                                          alt="Tortilleria"><br>
          </div>
        </div>
        <div id="map"></div>
        <br>
      </div>

    </th>
    <th style="border: hidden" WIDTH="25%">
      <img src="../images/Logos/logo_indice_qro.jpg" alt="IAS" width="100%"
           align="top"/>


    </th>
  </tr>
</table>


<!-- termina tabla ---------------------------------------------------------------------------------------------------------------->

