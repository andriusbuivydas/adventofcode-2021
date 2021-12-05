<?php

$freqs = [];
$handle = fopen('input.txt', 'r');
while (($data = fgets($handle)) !== false) {
    [$x1, $y1, $x2, $y2] = readPoints($data);
    $points = getPoints($x1, $y1, $x2, $y2);
    foreach ($points as [$x, $y]) {
        $key = "$x;$y";
        if (array_key_exists($key, $freqs)) {
            $freqs[$key]++;
        } else {
            $freqs[$key] = 1;
        }
    }
}
fclose($handle);

$total = 0;
foreach ($freqs as $freq) {
    if ($freq > 1) {
        $total++;
    }
}

printf("Total: %d\n", $total);

function readPoints(string $entry): array
{
    $str = str_replace(' -> ', ',', trim($entry));
    $values = explode(',', $str);
    return array_map(fn ($x) => (int) $x, $values);
}

function getPoints(int $x1, int $y1, int $x2, int $y2): array
{
    $points = [];
    if ($x1 === $x2) {
        if ($y1 <= $y2) {
            for ($i = $x1; $i <= $x2; $i++) {
                for ($j = $y1; $j <= $y2; $j++) {
                    $points[] = [$i, $j];
                }
            }
        } else {
            for ($i = $x1; $i <= $x2; $i++) {
                for ($j = $y2; $j <= $y1; $j++) {
                    $points[] = [$i, $j];
                }
            }
        }
    }

    if ($y1 === $y2) {
        if ($x1 <= $x2) {
            for ($i = $x1; $i <= $x2; $i++) {
                for ($j = $y1; $j <= $y2; $j++) {
                    $points[] = [$i, $j];
                }
            }
        } else {
            for ($i = $x2; $i <= $x1; $i++) {
                for ($j = $y1; $j <= $y2; $j++) {
                    $points[] = [$i, $j];
                }
            }
        }
    }

    if (abs($x1 - $x2) === abs($y1 - $y2)) {
        // diagonal
        $diff = abs($x2 - $x1);

        if ($x1 <= $x2 && $y1 <= $y2) {
            for ($i = 0; $i <= $diff; $i++) {
                $points[] = [$x1 + $i, $y1 + $i];
            }
        } elseif ($x1 <= $x2 && $y1 >= $y2) {
            for ($i = 0; $i <= $diff; $i++) {
                $points[] = [$x1 + $i, $y1 - $i];
            }
        } elseif ($x1 >= $x2 && $y1 <= $y2) {
            for ($i = 0; $i <= $diff; $i++) {
                $points[] = [$x1 - $i, $y1 + $i];
            }
        } elseif ($x1 >= $x2 && $y1 >= $y2) {
            for ($i = 0; $i <= $diff; $i++) {
                $points[] = [$x1 - $i, $y1 - $i];
            }
        }
    }

    return $points;
}
