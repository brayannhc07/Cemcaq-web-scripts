<?php

/**
 * @throws \Exception
 */
function readCsvFileHeader($filePath, $exclude = [], $delimiter = ',')
{
    // Open the file for reading
    if (($handle = fopen($filePath, 'r')) !== false) {
        // Read the first row as header
        $header = fgetcsv($handle, 1000, $delimiter);
        // Close the file handle
        fclose($handle);
    } else {
        throw new Exception("Could not open the file.");
    }

    // Convert the array to JSON
    return array_diff($header, $exclude);
}

/**
 * @throws \Exception
 */
function getColumnValuesFromCsv($filePath, $columnName, $delimiter = ',')
{
    $columnValues = [];

    // Open the file for reading
    if (($handle = fopen($filePath, 'r')) !== false) {
        // Read the first row as header
        if (($header = fgetcsv($handle, 1000, $delimiter)) !== false) {
            // Check if the required column exists
            if (in_array($columnName, $header)) {
                // Get the index of the required column
                $columnIndex = array_search($columnName, $header);

                // Loop through each row in the CSV file
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                    // Extract the value using the column index
                    $columnValues[] = $row[$columnIndex];
                }
            } else {
                throw new Exception("CSV file does not contain the required column.");
            }
        }
        // Close the file handle
        fclose($handle);
    } else {
        throw new Exception("Could not open the file.");
    }

    return $columnValues;
}

/**
 * @throws \Exception
 */
function filterDataByDateRange(
    $arrayData,
    $datetimeColumn,
    $startDate,
    $endDate
) {
    // Check if the datetime column exists in the data
    if ( ! isset($arrayData[$datetimeColumn])) {
        throw new Exception("DateTime column not found.");
    }

    // Convert the start and end dates to DateTime objects for comparison
    $startDate = strtotime($startDate);
    $endDate   = strtotime($endDate);

    // Initialize an array to store the filtered data
    $filteredData = [];

    // Iterate through each date in the datetime column
    foreach ($arrayData[$datetimeColumn] as $index => $date) {
        // Convert the current date to a DateTime object
        $currentDate = strtotime($date);
        // Check if the current date falls within the specified date range
        if ($currentDate >= $startDate && $currentDate <= $endDate) {
            // Add all columns' data for the matching date to the filtered data
            foreach ($arrayData as $key => $values) {
                $filteredData[$key][] = $values[$index];
            }
        }
    }

    // Return the filtered data
    return $filteredData;
}

/**
 * @throws \Exception
 */
function csvToCustomAssocArray(
    $filePath,
    $pivotColumn,
    $valueColumns
) {
    $result = array_fill_keys(array_merge([$pivotColumn], $valueColumns), []);

    // Open the file for reading
    if (($handle = fopen($filePath, 'r')) !== false) {
        // Read the first row as header
        if (($header = fgetcsv($handle, 1000, ',')) !== false) {
            // Check if the required columns exist
            if (in_array($pivotColumn, $header)
                && ! array_diff($valueColumns, $header)
            ) {
                // Get the index of the pivot column
                $pivotIndex = array_search($pivotColumn, $header);
                // Get the indexes of the value columns
                $valueIndexes = array_map(function ($col) use ($header) {
                    return array_search($col, $header);
                }, $valueColumns);

                // Loop through each row in the CSV file
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    // Extract the pivot value
                    $pivotValue = $row[$pivotIndex];

                    // Add the pivot value to the result array
                    $result[$pivotColumn][] = $pivotValue;

                    // Extract the values for each value column and add to result array
                    foreach ($valueColumns as $i => $col) {
                        $result[$col][] = $row[$valueIndexes[$i]];
                    }
                }
            } else {
                throw new Exception("CSV file does not contain the required columns.");
            }
        }
        // Close the file handle
        fclose($handle);
    } else {
        throw new Exception("Could not open the file.");
    }

    return $result;
}

function readCsvToArray($filename, $keyColumnName = null)
{
    // Check if the file exists and is readable
    if ( ! file_exists($filename) || ! is_readable($filename)) {
        return false;
    }

    $data = [];
    // Open the CSV file for reading
    if (($handle = fopen($filename, 'r')) !== false) {
        // Read the first row to get the headers
        $headers = fgetcsv($handle, 1000, ',');

        // Find the index of the key column, if specified
        $keyColumnIndex = $keyColumnName ? array_search($keyColumnName,
            $headers) : false;

        // Loop through each subsequent line in the CSV file
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            // Combine headers with row values
            $rowData = array_combine($headers, $row);

            if ($keyColumnIndex !== false && isset($row[$keyColumnIndex])) {
                // Use the value of the specified column as the key
                $data[$row[$keyColumnIndex]] = $rowData;
            } else {
                // Use a numeric index
                $data[] = $rowData;
            }
        }
        // Close the file after reading
        fclose($handle);
    }

    return $data;
}
