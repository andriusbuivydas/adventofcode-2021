<?php

$points = [
    ')' => 3,
    ']' => 57,
    '}' => 1197,
    '>' => 25137,
];

$totalPoints = 0;
foreach (readLines('input.txt') as $chars) {
    $incompleteChar = findIncompleteChar($chars);
    if (is_string($incompleteChar)) {
        $totalPoints += $points[$incompleteChar];
    }
}

printf("Total points: %d\n", $totalPoints);

function findIncompleteChar(array $chars): ?string
{
    $matches = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    $stack = new SplStack();

    foreach ($chars as $char) {
        if ($stack->isEmpty()) {
            $stack->push($char);
            continue;
        }

        $head = $stack->pop();
        if (!array_key_exists($head, $matches)) {
            return $head;
        }
        if ($matches[$head] !== $char) {
            $stack->push($head);
            $stack->push($char);
        }
    }

    return null;
}

function readLines(string $path): \Generator
{
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle, 2048)) !== false) {
        yield str_split($data[0]);
    }
    fclose($handle);
}
