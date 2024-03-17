<!  TABLA----- inicia tabla ---------------------------------------------------------------------------------------->

<meta http-equiv="refresh" content="3600">
<link href="../pronosticos/css/forms-concentracion.css" rel="stylesheet" />
<style>
  h2, h5 {
    text-align: center;
    margin: 0 !important;
  }

  type="text/css">
                 h5.tit_fecha {text-align: center}
  p {
    text-align: justify;
  }
</style>




<?php
// ESCRIBIR LA FECHA
date_default_timezone_set('America/Mexico_City');
$cadena= strftime("%A, %B %d, %Y", strtotime(date("Y-m-d") -1));
$horaActual = date("H");
//$newdate = replaceDayMonth($date);

$dayvalue = NULL;
$dayvalue = (strpos($cadena, 'Monday') !== false) ? str_ireplace('Monday', 'Lunes', $cadena) : $dayvalue;
$dayvalue = (strpos($cadena, 'Tuesday') !== false) ? str_ireplace('Tuesday', 'Martes', $cadena) : $dayvalue;
$dayvalue = (strpos($cadena, 'Wednesday') !== false) ? str_ireplace('Wednesday', 'Mi&eacute;rcoles', $cadena) : $dayvalue;
$dayvalue = (strpos($cadena, 'Thursday') !== false) ? str_ireplace('Thursday', 'Jueves', $cadena) : $dayvalue;
$dayvalue = (strpos($cadena, 'Friday') !== false) ? str_ireplace('Friday', 'Viernes', $cadena) : $dayvalue;
$dayvalue = (strpos($cadena, 'Saturday') !== false) ? str_ireplace('Saturday', 'S&aacute;bado', $cadena) : $dayvalue;
$dayvalue = (strpos($cadena, 'Sunday') !== false) ? str_ireplace('Sunday', 'Domingo', $cadena) : $dayvalue;

$newdate = NULL;
$newdate = (strpos($dayvalue, 'January') !== false) ? str_ireplace('January', 'enero', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'February') !== false) ? str_ireplace('February', 'febrero', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'March') !== false) ? str_ireplace('March', 'marzo', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'April') !== false) ? str_ireplace('April', 'abril', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'May') !== false) ? str_ireplace('May', 'mayo', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'June') !== false) ? str_ireplace('June', 'junio', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'July') !== false) ? str_ireplace('July', 'julio', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'August') !== false) ? str_ireplace('August', 'agosto', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'September') !== false) ? str_ireplace('September', 'septiembre', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'October') !== false) ? str_ireplace('October', 'octubre', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'November') !== false) ? str_ireplace('November', 'noviembre', $dayvalue) : $newdate ;
$newdate = (strpos($dayvalue, 'December') !== false) ? str_ireplace('December', 'diciembre', $dayvalue) : $newdate ;

echo "<p class='pDatos'>" . $newdate . " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; " . $horaActual . ":00 h </p>";

//        <p class='pDatos '>Esta informaci&oacute;n se actualiza cada hora - Haga click sobre la tabla para ver recomendaciones</p>";

function getNumberColor($colorName){
    $colorNumber = 0;
    if($colorName =="#BDD7EE"){
        $colorNumber = 0; // BLUE LIGH
    }
    else if($colorName =="#FFFFFF"){
        $colorNumber = 1; // BLANCO
    }
    else if($colorName =="#00E400"){
        $colorNumber = 2; // VERDE
    }
    else if($colorName =="#FFFF00"){
        $colorNumber = 3; // AMARILLO
    }
    else if($colorName =="#FF7E00"){
        $colorNumber = 4; // NARANJA
    }
    else if($colorName =="#FF0000"){
        $colorNumber = 5; // ROJO
    }
    else if($colorName =="#8F3F97"){
        $colorNumber = 6; // MORADO
    }
    return $colorNumber;
}
function getUrlArticle($colorNumber){
    $urlArticle = 0;
    if($colorNumber == 0 ){
        $urlArticle = ""; // BLUE LIGH
    }
    else if($colorNumber == 1 ){
        $urlArticle = ""; // BLANCO
    }
    else if($colorNumber == 2){
        $urlArticle = "http://aire.cemcaq.mx/buena-calidad-del-aire-riesgo-bajo/"; // VERDE
    }
    else if($colorNumber == 3){
        $urlArticle = "http://aire.cemcaq.mx/recomendaciones/"; // AMARILLO
    }
    else if($colorNumber == 4){
        $urlArticle = "http://aire.cemcaq.mx/mala-calidad-del-aire-riesgo-alto/"; // NARANJA
    }
    else if($colorNumber == 5){
        $urlArticle = "http://aire.cemcaq.mx/calidad-del-aire-muy-mala-riesgo-muy-alto/"; // ROJO
    }
    else if($colorNumber == 6){
        $urlArticle = "http://aire.cemcaq.mx/extremadamente-mala-calidad-del-aire-riesgo-extremadamente-alto/"; // MORADO
    }
    return $urlArticle ;
}
?>



<div class="container" >



    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $directorio = '../images/Reporte_calidad_aire/Reporte_horario/tablamapa.csv';

    try {
        $pathFile = $directorio;



        $url0 = "";
        $url1 = "";
        $url2 = "";
        $url3 = "";
        $url4 = "";
        $url5 = "";
        $url6 = "";
        $url7 = "";

        if(filesize($pathFile) > 0)
        {
            $arqivo = str_replace('\\', '', $pathFile);
            $fp = fopen($arqivo, "r");
            if($fp)
            {
                $aux = 0;
                $FechaAux="";
                $colorsCEN = array();
                $colorsCAP = array();
                $colorsCOR = array();
                $colorsEMA = array();
                $colorsEPG = array();
                $colorsFEO = array();
                $colorsJOV = array();
                $colorsSJR = array();


                //Recorremos el archivo
                while (($datos = fgetcsv($fp, ",")) == true)
                {
                    $numCols = count($datos);
                    if($numCols >= 9){
                        $num = 0;
                        if($aux > 0){
                            $vals1 = explode ("@", $datos[1]);
                            $num = getNumberColor($vals1[1]);
                            array_push($colorsCEN,$num);

                            $vals2 = explode ("@", $datos[2]);
                            $num = getNumberColor($vals2[1]);
                            array_push($colorsCAP ,$num);

                            $vals3 = explode ("@", $datos[3]);
                            $num = getNumberColor($vals3[1]);
                            array_push($colorsCOR , $num);

                            $vals4 = explode ("@", $datos[4]);
                            $num = getNumberColor($vals4[1]);
                            array_push($colorsEMA, $num);

                            $vals5 = explode ("@", $datos[5]);
                            $num = getNumberColor($vals5[1]);
                            array_push($colorsEPG, $num);


                            $vals6 = explode ("@", $datos[6]);
                            $num = getNumberColor($vals6[1]);
                            array_push($colorsFEO , $num);

                            $vals7 = explode ("@", $datos[7]);
                            $num = getNumberColor($vals7[1]);
                            array_push($colorsJOV, $num);

                            $vals8 = explode ("@", $datos[8]);
                            $num = getNumberColor($vals8[1]);
                            array_push($colorsSJR , $num);

                        }
                        $aux = $aux +1;
                    }
                }
                $url0 = getUrlArticle(max($colorsCEN));
                $url1 = getUrlArticle(max($colorsCAP));
                $url2 = getUrlArticle(max($colorsCOR));
                $url3 = getUrlArticle(max($colorsEMA));
                $url4 = getUrlArticle(max($colorsEPG));
                $url5 = getUrlArticle(max($colorsFEO));
                $url6 = getUrlArticle(max($colorsJOV));
                $url7 = getUrlArticle(max($colorsSJR));
                //Cerramos el archivo
                fclose($fp);
            }
            else {
                $url0 = "#top";
                $url1 = "#top";
                $url2 = "#top";
                $url3 = "#top";
                $url4 = "#top";
                $url5 = "#top";
                $url6 = "#top";
                $url7 = "#top";
            }
        }

        // LEER EL ARCHIVO
        // EL ARCHIVO TIENE CONTENIDO
        if(filesize($pathFile) > 0)
        {
            $arqivo = str_replace('\\', '', $pathFile);
            $fp = fopen($arqivo, "r");
            if($fp)
            {
                $tblCon = "\t\n<table align=center style='width:100%; min-width: 250px;'>\n";
                //$tblCon = "<table align=center style='width:80%;'>";
                //Recorremos el archivo
                $auxCount = 0;
                while (($datos = fgetcsv($fp, ",")) == true)
                {
                    $val0 = "";
                    $col0 = "";
                    $val1 = "";
                    $col1 = "";
                    $val2 = "";
                    $col2 = "";
                    $val3 = "";
                    $col3 = "";
                    $val4 = "";
                    $col4 = "";
                    $val5 = "";
                    $col5 = "";
                    $val6 = "";
                    $col6 = "";
                    $val7 = "";
                    $col7 = "";
                    $val8 = "";
                    $col8 = "";


                    $numCols = count($datos);
                    if($numCols >= 9){
                        $val0 = $datos[0];
                        $val1 = $datos[1];
                        $val2 = $datos[2];
                        $val3 = $datos[3];
                        $val4 = $datos[4];
                        $val5 = $datos[5];
                        $val6 = $datos[6];
                        $val7 = $datos[7];
                        $val8 = $datos[8];

                        if($auxCount == 0){
                            $tblCon .= "<thead>";
                            $tblCon .= "<tr>";
                            $tblCon .= "<th class='th15'>Estaci&oacute;n /<br />Contaminante</th>";

                            $hder = explode ("@", $val1);
                            $estName = $hder[0];
                            $estAbr = $hder[1];
                            $url = $hder[2];


                            $hder = explode ("@", $val2);
                            $estName = $hder[0];
                            $estAbr = $hder[1];
                            $url = $hder[2];
                            $tblCon .= "\t\t<th class='th15'>
				 <div>
                                     <a href='" . $url . "' target='_blank'>
                                      <input type='image' class='imgEst' src='../pronosticos/iconos/estacion/Icono" .$estAbr. ".png' alt='Estacion' />
                                    </a>
                                 </div>
				 <div>" . $estName. "</div>
				 <div>(" . $estAbr . ")</div></th>\n";

                            $hder = explode ("@", $val3);
                            $estName = $hder[0];
                            $estAbr = $hder[1];
                            $url = $hder[2];
                            $tblCon .= "\t\t<th class='th15'>
				  <div>
                                     <a href='" . $url . "' target='_blank'>
                                      <input type='image' class='imgEst' src='../pronosticos/iconos/estacion/Icono" .$estAbr. ".png' alt='Estacion' />
                                    </a>
                                 </div>
				 <div>" . $estName. "</div>
				 <div>(" . $estAbr . ")</div></th>\n";

                            $hder = explode ("@", $val4);
                            $estName = $hder[0];
                            $estAbr = $hder[1];
                            $url = $hder[2];


                            $hder = explode ("@", $val5);
                            $estName = $hder[0];
                            $estAbr = $hder[1];
                            $url = $hder[2];
                            $tblCon .= "\t\t<th class='th15'>
                                  <div>
                                     <a href='" . $url . "' target='_blank'>
                                      <input type='image' class='imgEst' src='../pronosticos/iconos/estacion/Icono" .$estAbr. ".png' alt='Estacion' />
                                    </a>
                                 </div>
				 <div>" . $estName. "</div>
				 <div>(" . $estAbr . ")</div></th>\n";

                            $hder = explode ("@", $val6);
                            $estName = $hder[0];
                            $estAbr = $hder[1];
                            $url = $hder[2];
                            $tblCon .= "\t\t<th class='th15'>
                              <div>
                                 <a href='" . $url . "' target='_blank'>
                                  <input type='image' class='imgEst' src='../pronosticos/iconos/estacion/Icono" .$estAbr. ".png' alt='Estacion' />
                                </a>
                             </div>
             <div>" . $estName. "</div>
             <div>(" . $estAbr . ")</div></th>\n";


                            $hder = explode ("@", $val7);
                            $estName = $hder[0];
                            $estAbr = $hder[1];
                            $url = $hder[2];
                            $tblCon .= "\t\t<th class='th15'>
                          <div>
                             <a href='" . $url . "' target='_blank'>
                              <input type='image' class='imgEst' src='../pronosticos/iconos/estacion/Icono" .$estAbr. ".png' alt='Estacion' />
                            </a>
                         </div>
         <div>" . $estName. "</div>
         <div>(" . $estAbr . ")</div></th>\n";

                            $hder = explode ("@", $val8);
                            $estName = $hder[0];
                            $estAbr = $hder[1];
                            $url = $hder[2];
                            $tblCon .= "\t\t<th class='th15'>
				  <div>
                                     <a href='" . $url . "' target='_blank'>
                                      <input type='image' class='imgEst' src='../pronosticos/iconos/estacion/IconoSJR.png' alt='Estacion' />
                                    </a>
                                 </div>
				 <div>" . $estName. "</div>
				 <div>(" . $estAbr . ")</div></th>\n";


                            $tblCon .= "\t</tr>\n</thead>\n<tbody>\n";

                            $header = "<thead>
					<tr><th>Contaminante</th>
					<th><div>I.C.A</div></th></tr>
				</thead>
				<tbody>";

                        }
                        else
                        {

                            $vals1 = explode ("@", $datos[1]);
                            if($auxCount == 1){
                                //$valsAux = explode ("&", $vals1[0]);
                                if (sizeof($vals1) > 1){
                                    $p=str_replace("/","-",$vals1[0]);
                                    $FechaAux   =  "&Uacute;ltima actualizaci&oacute;n " . date('d/m/Y H:i', strtotime($p));
                                    if (is_numeric($vals1[1])){
                                        $val1 =  $vals1[1];
                                    }
                                    else{
                                        $val1=null;

                                    }
                                    if (isset($vals1[2])){
                                        $col1=$vals1[2];
                                    }
                                    else
                                    {
                                        $col1="#FFFFFF";
                                    }
                                }
                                else {
                                    $FechaAux = "";
                                    $val1 =  $vals1[0];
                                }
                            }
                            else {
                                $val1 = $vals1[0];
                                $col1 = $vals1[1];

                            }


                            //$vals1 = explode ("@", $datos[1]);
                            //$val1 = $vals1[0];
                            //$col1 = $vals1[1];

                            $vals2 = explode ("@", $datos[2]);
                            $val2 = $vals2[0];// ($vals2[0] == "NULL" || $vals2[0] == "SM") ? "ND" : $vals2[0];
                            $col2 = $vals2[1];

                            $vals3 = explode ("@", $datos[3]);
                            $val3 = $vals3[0];// ($vals3[0] == "NULL" || $vals3[0] == "SM") ? "ND" : $vals3[0];
                            $col3 = $vals3[1];

                            //	$vals4 = explode ("@", $datos[4]);
                            //	$val4 = $vals4[0];// ($vals4[0] == "NULL" || $vals4[0] == "SM") ? "ND" : $vals4[0];
                            //	$col4 = $vals4[1];

                            $vals5 = explode ("@", $datos[5]);
                            $val5 =  $vals5[0];// ($vals5[0] == "NULL" || $vals5[0] == "SM") ? "ND" : $vals5[0];
                            $col5 = $vals5[1];

                            $vals6 = explode ("@", $datos[6]);
                            $val6 = $vals6[0];// ($vals6[0] == "NULL" || $vals6[0] == "SM") ? "ND" : $vals6[0];
                            $col6 = $vals6[1];

                            $vals7 = explode ("@", $datos[7]);
                            $val7 = $vals7[0];// ($vals7[0] == "NULL" || $vals7[0] == "SM") ? "ND" : $vals7[0];
                            $col7 = $vals7[1];

                            $vals8 = explode ("@", $datos[8]);
                            $val8 = $vals8[0];// ($vals8[0] == "NULL" || $vals8[0] == "SM") ? "ND" : $vals8[0];
                            $col8 = $vals8[1];


                            $conts = explode ("@", $val0);
                            $cont  = $conts[0];
                            $nameC = $conts[1];
                            $urlC  = $conts[2];

                            //$tblCon .= "\t\t<td class='pContaminante'><p>" . $val0 . "</p></td>\n";
                            $tblCon .= "\t\t<td style='text-align: center;'>	 
         <a class='aCont' href='" . $urlC  . "' target='_blank'> "  . $cont . "</a>        
	 <p class='pCont'>"  . $nameC . "</p>        
     </td>\n";


                            $tblCon .= "\t\t<td style='width:100%; height:100%; background-color:". $col2 . ";'><div class='divChart' ><p class='spanInlineYC'><a class='spanInlineYC' style='text-decoration:none' title='Ver recomendaciones' target='_blank' href=". $url1 .">" . $val2 . "</a></p></div></td>\n";
                            $tblCon .= "\t\t<td style='width:100%; height:100%; background-color:". $col3 . ";'><div class='divChart' ><p class='spanInlineYC'><a class='spanInlineYC' style='text-decoration:none' title='Ver recomendaciones' target='_blank' href=". $url2 .">" . $val3 . "</a></p></div></td>\n";
                            //$tblCon .= "\t\t<td style='width:100%; height:100%; background-color:". $col4 . ";'><div class='divChart' ><p class='spanInlineYC'><a class='spanInlineYC' style='text-decoration:none' title='Ver recomendaciones' target='_blank' href=". $url3 .">" . $val4 . "</a></p></div></td>\n";
                            $tblCon .= "\t\t<td style='width:100%; height:100%; background-color:". $col5 . ";'><div class='divChart' ><p class='spanInlineYC'><a class='spanInlineYC' style='text-decoration:none' title='Ver recomendaciones' target='_blank' href=". $url4 .">" . $val5 . "</a></p></div></td>\n";
                            $tblCon .= "\t\t<td style='width:100%; height:100%; background-color:". $col6 . ";'><div class='divChart' ><p class='spanInlineYC'><a class='spanInlineYC' style='text-decoration:none' title='Ver recomendaciones' target='_blank' href=". $url5 .">" . $val6 . "</a></p></div></td>\n";
                            $tblCon .= "\t\t<td style='width:100%; height:100%; background-color:". $col7 . ";'><div class='divChart' ><p class='spanInlineYC'><a class='spanInlineYC' style='text-decoration:none' title='Ver recomendaciones' target='_blank' href=". $url6 .">" . $val7 . "</a></p></div></td>\n";
                            $tblCon .= "\t\t<td style='width:100%; height:100%; background-color:". $col8 . ";'><div class='divChart' ><p class='spanInlineYC'><a class='spanInlineYC' style='text-decoration:none' title='Ver recomendaciones' target='_blank' href=". $url7 .">" . $val8 . "</a></p></div></td>\n";



                            $tblCon .= "\t</tr>\n";
                        }
                    }
                    $auxCount = $auxCount +1;
                }
                $tblCon .= "</tbody>\n</table>\n<br />";

                // ESCRIBIR HORA DE ACTUALIZACIÓN
                echo "<p class='pDatos '>Esta informaci&oacute;n se actualiza cada hora <br> 
                                 ". $FechaAux . " h <br> 
                                 Haga clic sobre las celdas de la tabla para ver recomendaciones</p>";
                //        echo "<p class='pDatos '> ". $FechaAux . " h</p>";
                //ESCRIBIR LA TABLA
                echo $tblCon ."</br>";
            }
            //Cerramos el archivo
            fclose($fp);
        }
        else {
            echo "El archivo de concentraciones horario esta vacio";
        }
    } catch (Exception $e) {
        echo 'Excepción al leer el archivo de datos: ',  $e->getMessage(), "\n";
    }
    ?>


    <table align=center style='width:80%;'>
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

<p>Notas<br />Los índices de aire y salud (IAS) y las categorías del mismo, incluyendo las <a href="http://aire.cemcaq.mx/wp-content/uploads/2021/04/Bandas_IAS_NOM_172_SEMARNAT_2019.jpg" target="_blank">BANDAS CROMÁTICAS</a>&nbsp;asociadas a la calidad del aire y nivel de riesgo, son las establecidas en la Norma Oficial Mexicana NOM-172-SEMARNAT-2019 "Lineamientos para la obtención y comunicación del índice de Calidad del Aire y Riesgos a la salud". Los valores que se reportan son los índices para el día y hora indicada. El área de influencia de las estaciones de monitoreo es de aproximadamente dos kilómetros de radio.</p>
