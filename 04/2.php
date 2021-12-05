<?php

// correct result, but really dislike the solution

$n = 5;

$pickedNumbers = readPickedNumbers('input.txt');
$boards = readBoards('input.txt', $n);

foreach ($pickedNumbers as $pickedNumber) {
    printf("Picked number: %d\n", $pickedNumber);
    markBoards($boards, $pickedNumber);

    while (($winningBoardIndex = findWinningBoardIndex($boards, $n)) !== null) {
        if (is_int($winningBoardIndex)) {
            if (count($boards) === 1) {
                break 2;
            }
            unset($boards[$winningBoardIndex]);
            $boards = array_values($boards);
        }
    }
}

$board = $boards[0];

$total = 0;
foreach ($board as $entry) {
    if ($entry > 0) {
        $total += $entry;
    }
}
$score = $total * $pickedNumber;

printf("Total: %d\n", $total);
printf("Last picked number: %d\n", $pickedNumber);
printf("Score: %d\n", $score);

function readPickedNumbers(string $path): array
{
    $pickedNumbers = [];
    $handle = fopen($path, 'r');
    while (($data = fgetcsv($handle)) !== false) {
        foreach ($data as $pickedNumber) {
            $pickedNumbers[] = (int) $pickedNumber;
        }
        break;
    }
    fclose($handle);

    return $pickedNumbers;
}

function readBoards(string $path, int $n): array
{
    $index = 0;
    $boardsNumbers = [];
    $handle = fopen($path, 'r');
    while (($data = fgetcsv($handle)) !== false) {
        $index++;
        if ($index < 2 || $data[0] === null) {
            continue;
        }
        $entries = preg_replace('/\s+/', ',', trim($data[0]));
        $entries = explode(',', $entries);
        foreach ($entries as $entry) {
            $boardsNumbers[] = (int) $entry;
        }
    }
    fclose($handle);

    return array_chunk($boardsNumbers, $n * $n);
}

function getRows(array $board, int $n): array
{
    $rows = [];

    for ($i = 0; $i < $n; $i++) {
        $row = [];
        for ($j = 0; $j < $n; $j++) {
            $row[] = $board[$i * $n + $j];
        }

        $rows[] = $row;
    }

    return $rows;
}

function getColumns(array $board, int $n): array
{
    $columns = [];

    for ($i = 0; $i < $n; $i++) {
        $column = [];
        for ($j = 0; $j < $n * $n; $j = $j + $n) {
            $column[] = $board[$i + $j];
        }

        $columns[] = $column;
    }

    return $columns;
}

function markPickedNumber(array &$board, int $pickedNumber): void
{
    foreach ($board as &$entry) {
        if ($entry === $pickedNumber) {
            if ($entry === 0) {
                $entry = -100;
            } else {
                $entry = $entry * -1;
            }
        }
    }
}

function isRowOrColumnMarked(array $entries): bool
{
    $isMarked = false;
    foreach ($entries as $entry) {
        if ($entry >= 0) {
            $isMarked = false;
            break;
        } else {
            $isMarked = true;
        }
    }

    return $isMarked;
}

function markBoards(array &$boards, int $pickedNumber): void
{
    foreach ($boards as $index => &$board) {
        markPickedNumber($board, $pickedNumber);
    }
}

function findWinningBoardIndex(array $boards, int $n): ?int
{
    foreach ($boards as $index => &$board) {
        $rows = getRows($board, $n);
        foreach ($rows as $row) {
            if (isRowOrColumnMarked($row)) {
                return $index;
            }
        }

        $columns = getColumns($board, $n);
        foreach ($columns as $column) {
            if (isRowOrColumnMarked($column)) {
                return $index;
            }
        }
    }

    return null;
}
