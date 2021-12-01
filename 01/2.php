<?php

$handle = fopen('input.txt', 'r');

$increases = 0;
$measurements = [];
while (($data = fgetcsv($handle)) !== false) {
    $measurements[] = (int) $data[0];
}
fclose($handle);

$totalMeasurements = count($measurements);
$length = 3;

$slicesSums = [];
for ($i = 0; $i < $totalMeasurements - 2; $i++) {
    $slicesSums[] = array_sum(array_slice($measurements, $i, $length));
}

$increases = 0;
$previous = $current = null;
foreach ($slicesSums as $sliceSum) {
    $previous = $current;
    $current = $sliceSum;

    if (is_int($previous) && is_int($current) && $previous < $current) {
        $increases++;
    }
}

var_dump($increases);
