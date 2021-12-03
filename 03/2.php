<?php

$handle = fopen('input.txt', 'r');

$binLength = 0;
$measurements = [];
while (($data = fgetcsv($handle)) !== false) {
    $splitData = str_split($data[0]);
    $measurements[] = $splitData;
    $binLength = count($splitData);
}
fclose($handle);

$mostMeasurements = $measurements;
for ($i = 0; $i < $binLength; $i++) {
    $mostMeasurements = filterMeasurements($i, $mostMeasurements, 'most');
    if (count($mostMeasurements) === 1) {
        break;
    }
}
$oxygenGeneratorRating = bindec(implode('', $mostMeasurements[0]));
echo PHP_EOL;
printf("Oxygen Generator rating: %d\n", $oxygenGeneratorRating);
echo PHP_EOL;

$leastMeasurements = $measurements;
for ($i = 0; $i < $binLength; $i++) {
    $leastMeasurements = filterMeasurements($i, $leastMeasurements, 'least');
    if (count($leastMeasurements) === 1) {
        break;
    }
}
$CO2ScrubberRating = bindec(implode('', $leastMeasurements[0]));
echo PHP_EOL;
printf("CO2 Scrubber rating: %d\n", $CO2ScrubberRating);
echo PHP_EOL;

$lifeSupportRating = $oxygenGeneratorRating * $CO2ScrubberRating;
echo PHP_EOL;
printf("Life Support rating: %d\n", $lifeSupportRating);
echo PHP_EOL;

function filterMeasurements(int $index, array $measurements, string $direction): array
{
    $freqs = [0, 0];
    foreach ($measurements as $measurement) {
        $measurement[$index] === '0' ? $freqs[0]++ : $freqs[1]++;
    }
    $number = match ($direction) {
        'most' => $freqs[0] > $freqs[1] ? 0 : 1,
        'least' => $freqs[0] <= $freqs[1] ? 0 : 1,
        default => throw new \InvalidArgumentException(),
    };

    $selected = [];
    foreach ($measurements as $measurement) {
        if ($number === (int) $measurement[$index]) {
            $selected[] = $measurement;
        }
    }

    return $selected;
}
