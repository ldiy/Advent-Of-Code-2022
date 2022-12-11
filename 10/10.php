<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    $result = [];
    $lines = lines($filename);
    foreach ($lines as $line) {
        $result[] = explode(' ', $line);
    }

    return $result;
}

function part1($input): int
{
    $result = 0;
    $cycle = 1;
    $x = 1;
    foreach ($input as $line) {
        if (($cycle-20) % 40 == 0) {
            $result += $x * $cycle;
        }
        if ($line[0] == 'addx') {
            $cycle++;
            if (($cycle-20) % 40 == 0) {
                $result += $x * $cycle;
            }
            $x += intval($line[1]);
        }
        $cycle++;
    }

    return $result;
}

function draw(&$screen, int $x, int $cycle): void
{
    $row = floor(($cycle-1) / 40);
    $col = ($cycle-1) % 40;

    if ($x - 1 == $col || $x  == $col || $x +1 == $col) {
        $screen[$row][$col] = '██';
    }
}

function part2($input): int
{
    $result = 0;
    $cycle = 1;
    $x = 1;
    $screen = [];
    for ($i = 0; $i < 6; $i++) {
        $screen[] = array_fill(0, 40, '  ');
    }

    foreach ($input as $line) {
        draw($screen, $x, $cycle);
        if ($line[0] == 'addx') {
            $cycle++;
            draw($screen, $x, $cycle);
            $x += intval($line[1]);
        }
        $cycle++;
    }

    foreach ($screen as $row) {
        echo implode('', $row) . PHP_EOL;
    }

    return $result;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;