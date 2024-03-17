<?php
global $controlFilePath;
//$controlFilePath = "../images/Reporte_calidad_aire/Reporte_horario/control.csv";
$controlFilePath = "control.csv";
function saveControl() {
    global $_POST;
    global $controlFilePath;
    // Se encarga de reescribir el archivo de control dependiendo los datos que se le envían.
    if( isset( $_POST['control'] ) && file_exists( $controlFilePath ) ) {
        // Obtener los datos y el archivo original
        $control = $_POST['control'];
        $csv = array_map( "str_getcsv", file( $controlFilePath ) );
        for( $row = 0; $row < count( $csv ); $row++ ) {
            if( $row == 0 ) {
                $rowHeader = $csv[$row];
                continue;
            }
            for( $col = 0; $col < count($csv[0]); $col++ ) {
                if( $col == 0) {
                    $rowName = $csv[$row][$col];
                }
                else {
                    // Determina si está activado o no el módulo
                    // Se debe recibir el nombre del módulo para activarlo, por defecto estará desactivado
                    $status = ( array_key_exists( $rowName, $control ) && in_array( $rowHeader[$col], $control[$rowName]) ) ? 1 : 0;
                    // Actualiza al nuevo status
                    $csv[$row][$col] = $status;
                }
            }
        }
        $fp = fopen( $controlFilePath, "w" );
        foreach( $csv as $fields ) {
            fputcsv( $fp, $fields );
        }
        fclose( $fp );
    }
}

function printControlTable() {
    global $controlFilePath;
    if( !file_exists($controlFilePath) ) {
        // Muestra un mensaje cuando no exista el archivo.
        echo '<p class="lead"> 
            El archivo control.csv no se encuentra, verifícalo con un administrador.
          </p>';
    } else {
        // Imprime una tabla con los controles según los datos guardados
        echo '<table class="table"><thead>';
        // Leer los datos desde el  csv
        $csv = array_map( "str_getcsv", file( $controlFilePath ) );

        for( $rowIndex = 0; $rowIndex < count($csv); $rowIndex++ ) {
            $rowName = '';
            echo '<tr>';
            $cellTag = $rowIndex === 0 ? 'th' : 'td';
            for( $columnIndex = 0; $columnIndex < count($csv[0]); $columnIndex++ ) {
                echo '<' . $cellTag . '>';
                if( $rowIndex === 0 || $columnIndex === 0 ) {
                    echo $csv[$rowIndex][$columnIndex];
                    $rowName = $csv[$rowIndex][0];
                } else {
                    $checked = $csv[$rowIndex][$columnIndex] > 0 ? "checked" : "";
                    echo '<div class="form-check form-switch">
                  <input
                    class="form-check-input" 
                    type="checkbox" 
                    '. $checked . ' 
                    name="control[' . $rowName . '][]"
                    value="' . $csv[0][$columnIndex] . '">
                </div>';
                }
                echo '</' . $cellTag . '>';
            }
            echo '</tr>';
            if( $rowIndex === 0 ) {
                echo '</thead> <tbody>';
            }
        }
    }

    echo '</tbody> </table>';

}

saveControl();
?>

<html>
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>Prueba Control Mapa</title>
</head>
<body>
<h3>Datos Actuales</h3>
<form method="post" action="">
    <?php printControlTable(); ?>
  <button class="btn btn-primary" type="submit">Guardar Cambios</button>
</form>
</body>
</html>
