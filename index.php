<?php
 $weekdays = array(
  'domingo', 'lunes', 'martes', 'miércoles',
  'jueves', 'viernes', 'sábado'
 );
 $months = array(null,
  'enero', 'febrero', 'marzo', 'abril',
  'mayo', 'junio', 'julio', 'agosto',
  'septiembre', 'octubre', 'noviembre',
  'diciembre'
 );
 $weekday = $weekdays[ date('w') ];
 $month   = $months[ date('n') ];
 $day     = date('j');
 $year    = date('Y');

 echo "$weekday, $day $month $year";
?>