<?php

$positions = readPositions('input.txt');

$min = min($positions);
$max = max($positions);
printf("Min position: %d, Max position: %d\n", $min, $max);

$fuelConsumption = [];
for ($i = $min; $i <= $max; $i++) {
    $fuelConsumption[$i] = calculateFuelConsumption($positions, $i);
}

asort($fuelConsumption);
foreach ($fuelConsumption as $index => $fuel) {
    printf("Min fuel consumption would be to move to %d position. Fuel needed: %d\n", $index, $fuel);
    break;
}

function readPositions(string $path): array
{
    $positions = [];
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle)) !== false) {
        $positions = $data;
    }
    fclose($handle);
    $positions = array_map(fn ($x) => (int) $x, $positions);

    return $positions;
}

function calculateFuelConsumption(array $positions, int $i): int
{
    $consumption = 0;
    foreach ($positions as $position) {
        $consumption += abs($position - $i);
    }

    return $consumption;
}

