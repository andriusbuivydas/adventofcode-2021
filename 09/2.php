<?php

$matrix = readMatrix('input.txt');

$lowPointsIndices = findLowPointsIndices($matrix);

$basinsSizes = [];
foreach ($lowPointsIndices as $lowPointsIndex) {
    $basinsSizes[] = findBasinSize($matrix, $lowPointsIndex);
}

sort($basinsSizes);
$basinsSizes = array_reverse($basinsSizes);
$basinsSizes = array_slice($basinsSizes, 0, 3);
$result = array_product($basinsSizes);

var_dump($result);

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

function findLowPointsIndices(array $matrix): array
{
    $lowPointsIndices = [];

    $ni = count($matrix);
    $nj = count($matrix[0]);
    for ($i = 0; $i < $ni; $i++) {
        for ($j = 0; $j < $nj; $j++) {
            $element = $matrix[$i][$j];

            $adjacentIndices = findAdjacentIndices([$i, $j], $ni, $nj);

            $adjacentElements = [];
            foreach ($adjacentIndices as [$x, $y]) {
                $adjacentElements[] = $matrix[$x][$y];
            }

            $minAdjacent = min($adjacentElements);
            if ($element < $minAdjacent) {
                $lowPointsIndices[] = [$i, $j];
            }
        }
    }

    return $lowPointsIndices;
}

function findAdjacentIndices(array $elementIndices, int $ni, int $nj): array
{
    $adjacentIndices = [];

    [$i, $j] = $elementIndices;

    if ($j - 1 >= 0) {
        $adjacentIndices[] = [$i, $j - 1]; // left
    }
    if ($j + 1 < $nj) {
        $adjacentIndices[] = [$i, $j + 1]; // right
    }
    if ($i - 1 >= 0) {
        $adjacentIndices[] = [$i - 1, $j]; // up
    }
    if ($i + 1 < $ni) {
        $adjacentIndices[] = [$i + 1, $j]; // down
    }

    return $adjacentIndices;
}

function findBasinSize(array $matrix, array $lowPointIndices): int
{
    $ni = count($matrix);
    $nj = count($matrix[0]);

    $basinIndices = [$lowPointIndices];
    $indicesPool = [$lowPointIndices];
    while (!empty($indicesPool)) {
        [$i, $j] = array_pop($indicesPool);
        $point = $matrix[$i][$j];

        $adjacentIndices = findAdjacentIndices([$i, $j], $ni, $nj);
        foreach ($adjacentIndices as [$x, $y]) {
            $adjacentPoint = $matrix[$x][$y];
            if ($point + 1 === $adjacentPoint && $adjacentPoint !== 9 && !in_array([$x, $y], $basinIndices)) {
                $basinIndices[] = [$x, $y];
                $indicesPool[] = [$x, $y];
            }
        }
    }

    return count($basinIndices);
}
