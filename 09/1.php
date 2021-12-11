<?php

$matrix = readMatrix('input.txt');

$lowPoints = [];

$ni = count($matrix);
$nj = count($matrix[0]);
for ($i = 0; $i < $ni; $i++) {
    for ($j = 0; $j < $nj; $j++) {
        $element = $matrix[$i][$j];

        $adjacentElements = [];
        if ($j - 1 >= 0) {
            $adjacentElements[] = $matrix[$i][$j - 1]; // left, i, j - 1
        }
        if ($j + 1 < $nj) {
            $adjacentElements[] = $matrix[$i][$j + 1]; // right, i, j + 1
        }
        if ($i - 1 >= 0) {
            $adjacentElements[] = $matrix[$i - 1][$j]; // up, i - 1, j
        }
        if ($i + 1 < $ni) {
            $adjacentElements[] = $matrix[$i + 1][$j]; // down, i + 1, j
        }
        //printf("el: %d\n", $element);
        //printf("ad: %s\n", json_encode($adjacentElements));
        //echo PHP_EOL;

        $minAdjacent = min($adjacentElements);
        if ($element < $minAdjacent) {
            $lowPoints[] = $element;
            //printf("Found smallest %d\n", $element);
        }
    }
}

$riskLevels = array_map(fn ($x) => $x + 1, $lowPoints);
$riskLevelsSum = array_sum($riskLevels);

printf("Risk levels sum: %d\n", $riskLevelsSum);

function readMatrix(string $path): array
{
    $matrix = [];
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle)) !== false) {
        $row = str_split($data[0]);
        $row = array_map(fn ($x) => (int) $x, $row);
        $matrix[] = $row;
    }
    fclose($handle);

    return $matrix;
}
