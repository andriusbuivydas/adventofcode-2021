<?php

$matrix = readMatrix('input.txt');
$N = count($matrix);

printMatrix($matrix);

$step = 0;
while (!willAllFlash($matrix)) {
    [$matrix, $flashCount] = step($matrix);
    printMatrix($matrix);
    $step++;
}

printf("Step: %d\n", $step);

function step(array $matrix): array
{
    $N = count($matrix);

    // a) increase energy level by 1
    for ($i = 0; $i < $N; $i++) {
        for ($j = 0; $j < $N; $j++) {
            $matrix[$i][$j] = $matrix[$i][$j] + 1;
        }
    }

    [$m, $n] = readyToFlashIndices($matrix);
    while ($m >=0 && $n >= 0) {
        $adjacentIndices = findAdjacentIndices([$m, $n], $N);
        foreach ($adjacentIndices as [$x, $y]) {
            $matrix[$x][$y] += 1;
        }
        $matrix[$m][$n] = -1000;
        [$m, $n] = readyToFlashIndices($matrix);
    }

    $flashCount = 0;
    for ($i = 0; $i < $N; $i++) {
        for ($j = 0; $j < $N; $j++) {
            if ($matrix[$i][$j] < 0) {
                $matrix[$i][$j] = 0;
                $flashCount++;
            }
        }
    }

    return [$matrix, $flashCount];
}

function readyToFlashIndices(array $matrix): array
{
    $N = count($matrix);

    for ($i = 0; $i < $N; $i++) {
        for ($j = 0; $j < $N; $j++) {
            if ($matrix[$i][$j] > 9) {
                return [$i, $j];
            }
        }
    }

    return [-1, -1];
}

function willAllFlash(array $matrix): bool
{
    $N = count($matrix);

    for ($i = 0; $i < $N; $i++) {
        for ($j = 0; $j < $N; $j++) {
            if ($matrix[$i][$j] !== 0) {
                return false;
            }
        }
    }

    return true;
}

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

function printMatrix($matrix): void
{
    $N = count($matrix);
    for ($i = 0; $i < $N; $i++) {
        for ($j = 0; $j < $N; $j++) {
            echo $matrix[$i][$j];
        }
        echo PHP_EOL;
    }
    echo PHP_EOL;
}

function findAdjacentIndices(array $elementIndices, int $N): array
{
    $adjacentIndices = [];

    [$i, $j] = $elementIndices;

    if ($j - 1 >= 0) {
        $adjacentIndices[] = [$i, $j - 1]; // left
    }
    if ($i - 1 >= 0 && $j - 1 >= 0) {
        $adjacentIndices[] = [$i - 1, $j - 1]; // left/top
    }
    if ($i - 1 >= 0) {
        $adjacentIndices[] = [$i - 1, $j]; // top
    }
    if ($i - 1 >= 0 && $j + 1 < $N) {
        $adjacentIndices[] = [$i - 1, $j + 1]; // right/top
    }
    if ($j + 1 < $N) {
        $adjacentIndices[] = [$i, $j + 1]; // right
    }
    if ($i + 1 < $N && $j + 1 < $N) {
        $adjacentIndices[] = [$i + 1, $j + 1]; // right/bottom
    }
    if ($i + 1 < $N && $j >= 0) {
        $adjacentIndices[] = [$i + 1, $j]; // bottom
    }
    if ($i + 1 < $N && $j - 1 >= 0) {
        $adjacentIndices[] = [$i + 1, $j - 1]; // left/bottom
    }

    return $adjacentIndices;
}
