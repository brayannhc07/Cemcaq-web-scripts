<form action="/export-data/export_full.php" method="get">
    <div>
        <label for="picker_begin">Fecha y hora de inicio:</label>
        <input type="date" name="begin" id="picker_begin" required="">
    </div>
    <div>
        <label for="picker_end">Fecha y hora final:</label>
        <input type="date" name="end" id="picker_end" required="">
    </div>

    <button type="submit">Exportar a Csv</button>
</form>

Esta opción permite descargar todos los datos por estaciones de monitoreo seleccionada, seleccionado el periodo de fechas. (Datos disponibles año 2021)

<form action="/export-data/export_full.php" method="get">
  <div>
    <label for="picker_begin">Fecha y hora de inicio:</label>
    <input type="date" name="begin" id="picker_begin" required="">
  </div>
  <div>
    <label for="picker_end">Fecha y hora final:</label>
    <input type="date" name="end" id="picker_end" required="">
  </div>
  <div>
    <label for="station_name">Estación:</label>
    <select class="form-control" name="station" id="station_name" required="">
      <option>COR</option>
      <option>SJU</option>
      <option>CAP</option>
      <option>EMA</option>
      <option>FEO</option>
      <option>EPG</option>
      <option>JOV</option>
    </select>
  </div>
  <br><br>
  <button type="submit">Exportar a Csv</button>
</form>

Esta opción permite descargar los datos de todas las estaciones de monitoreo por contaminante/Compuesto  seleccionado, seleccionado el periodo de fechas. (Datos disponibles año 2021)

<form action="/export-data/export_full.php" method="get">
  <div>
    <label for="picker_begin">Fecha y hora de inicio:</label>
    <input type="date" name="begin" id="picker_begin" required="">
  </div>
  <div>
    <label for="picker_end">Fecha y hora final:</label>
    <input type="date" name="end" id="picker_end" required="">
  </div>
  <div>
    <label for="parameter_name">Contaminante/Compuesto:</label>
    <select class="form-control" name="parameter" id="parameter_name" required="">
      <option>VV</option>
      <option>DV</option>
      <option>PB</option>
      <option>TMP</option>
      <option>HR</option>
      <option>PP</option>
      <option>NO</option>
      <option>NO2</option>
      <option>NOx</option>
      <option>RS</option>
      <option>CO</option>
      <option>O3</option>
      <option>SO2</option>
      <option>PM2.5</option>
      <option>PM10</option>
    </select>
  </div>
  <br><br>
  <button type="submit">Exportar a Csv</button>
</form>
