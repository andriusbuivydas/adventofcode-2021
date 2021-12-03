<?php

$handle = fopen('input.txt', 'r');

$measurements = [];
while (($data = fgetcsv($handle)) !== false) {
    $entries = $data[0];
    foreach (str_split($entries) as $index => $entry) {
        $measurements[$index][] = $entry;
    }
}
fclose($handle);

$gammaRateBin = $epsilonRateBin = [];
foreach ($measurements as $measurement) {
    $gammaRateBin[] = calculateMostCommonBit($measurement);
    $epsilonRateBin[] = calculateLeastCommonBit($measurement);
}

$gammaRate = bindec(implode('', $gammaRateBin));
echo PHP_EOL;
printf("Gamma rate: %d\n", $gammaRate);
echo PHP_EOL;

$epsilonRate = bindec(implode('', $epsilonRateBin));
echo PHP_EOL;
printf("Epsilon rate: %d\n", $epsilonRate);
echo PHP_EOL;

$powerConsumption = $gammaRate * $epsilonRate;
echo PHP_EOL;
printf("Power consumption: %d\n", $powerConsumption);
echo PHP_EOL;

function calculateMostCommonBit(array $measurement): int
{
    [$zeroes, $ones] = array_count_values($measurement);

    return $zeroes > $ones ? 0 : 1;
}

function calculateLeastCommonBit(array $measurement): int
{
    [$zeroes, $ones] = array_count_values($measurement);

    return $zeroes < $ones ? 0 : 1;
}

