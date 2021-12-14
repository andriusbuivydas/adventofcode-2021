<?php

[$polymer, $rules] = readInput('input.txt');

// two slow!
for ($steps = 1; $steps <= 20; $steps++) {
    $polymer = step($polymer, $rules);
    //printPolymer($polymer);
}

$min = $max = null;
$frequencies = array_count_values($polymer);
foreach ($frequencies as $key => $frequency) {
    if ($min === null) {
        $min = $frequency;
    }
    if ($max === null) {
        $max = $frequency;
    }
    if ($frequency < $min) {
        $min = $frequency;
    }
    if ($frequency > $max) {
        $max = $frequency;
    }
}

$diff = $max - $min;
printf("Diff: %d\n", $diff);

function step(array $polymer, array $rules): array
{
    $adjustedPolymer = [];

    $N = count($polymer);
    for ($i = 0; $i < $N - 1; $i++) {
        $part = array_slice($polymer, $i, 2);
        $key = implode('', $part);
        $element = $rules[$key];
        if ($i === 0) {
            $adjustedPolymer[] = $part[0];
        }
        $adjustedPolymer[] = $element;
        $adjustedPolymer[] = $part[1];
    }

    return $adjustedPolymer;
}

function printPolymer(array $polymer): void
{
    foreach ($polymer as $element) {
        printf('%s', $element);
    }
    echo PHP_EOL;
}

function readInput(string $path): array
{
    $polymer = $rules = [];
    $handle = fopen('input.txt', 'r');
    $i = 0;
    while (($data = fgetcsv($handle)) !== false) {
        $i++;
        if ($data[0] === null) {
            continue;
        }
        if ($i === 1) {
            $polymer = str_split($data[0]);
            continue;
        }

        // CH -> B
        [$x, $y] = explode(' -> ', $data[0]);
        $rules[$x] = $y;
    }
    fclose($handle);

    return [$polymer, $rules];
}
