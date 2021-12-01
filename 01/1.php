<?php

$handle = fopen('input.txt', 'r');

$increases = 0;
$previous = $current = null;
while (($data = fgetcsv($handle)) !== false) {
    $previous = $current;
    $current = (int) $data[0];

    if (is_int($previous) && is_int($current) && $previous < $current) {
        $increases++;
    }
}
fclose($handle);

var_dump($increases);
