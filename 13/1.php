<?php

[$points, $foldPoints] = readInput('input.txt');

//printf("=== initial paper view ===\n");
//printPoints($points);

[$direction, $foldPoint] = reset($foldPoints);
$points = foldBy($points, $direction, $foldPoint);

//printf("=== paper view after first fold ===\n");
//printPoints($points);

printf("Total points: %d\n", count($points));

//$points = foldBy($points, 'x', 5);
//
//printf("=== paper view after first fold ===\n");
//printPoints($points);

function foldBy(array $points, string $direction, int $foldPoint): array
{
    $foldedPoints = [];
    foreach ($points as [$x, $y]) {
        if ($direction === 'y') {
            if ($y > $foldPoint) {
                if ($y % $foldPoint === 0) {
                    $foldedPoint = [$x, 0];
                } else {
                    $foldedPoint = [$x, $foldPoint - $y % $foldPoint];
                }

                $skip = false;
                foreach ($points as [$m, $n]) {
                    [$k, $l] = $foldedPoint;
                    if ($k === $m && $l === $n) {
                        $skip = true;
                    }
                }

                if ($skip === false) {
                    $foldedPoints[] = $foldedPoint;
                }
            } else {
                $foldedPoints[] = [$x, $y];
            }
        } elseif ($direction === 'x') {
            if ($x > $foldPoint) {
                if ($x % $foldPoint === 0) {
                    $foldedPoint = [0, $y];
                } else {
                    $foldedPoint = [$foldPoint - $x % $foldPoint, $y];
                }

                $skip = false;
                foreach ($points as [$m, $n]) {
                    [$k, $l] = $foldedPoint;
                    if ($k === $m && $l === $n) {
                        $skip = true;
                    }
                }

                if ($skip === false) {
                    $foldedPoints[] = $foldedPoint;
                }
            } else {
                $foldedPoints[] = [$x, $y];
            }
        }
    }

    return $foldedPoints;
}

function readInput(string $path): array
{
    $points = $foldPoints = [];
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle)) !== false) {
        if ($data[0] === null) {
            continue;
        }
        if (str_starts_with($data[0], 'fold along')) {
            $fp = explode('fold along ', $data[0]);
            $fp = explode('=', $fp[1]);
            $foldPoints[] = [$fp[0], (int) $fp[1]];
            continue;
        }

        $row = array_map(fn ($x) => (int) $x, $data);
        $points[] = $row;
    }
    fclose($handle);

    return [$points, $foldPoints];
}

function printPoints(array $points): void
{
    $R = $C = 0;
    foreach ($points as [$x, $y]) {
        if ($x > $R) {
            $R = $x;
        }
        if ($y > $C) {
            $C = $y;
        }
    }

    printf("\n");
    for ($j = 0; $j <= $C; $j++) {
        for ($i = 0; $i <= $R; $i++) {
            printf('%s', in_array([$i, $j], $points) ? '#' : '.');
        }
        printf("\n");
    }
    printf("\n");
}
