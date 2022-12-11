<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    // Read line by line
    $lines = lines($filename);
    $result = [];
    foreach ($lines as $line) {
        $result[] = str_split($line);
    }
    return $result;
}

function isVisibleFromOutside(array &$map, int $x, int $y): bool
{
    // Left and right
    $leftPart = array_slice($map[$y], 0, $x+1);
    $rightPart = array_slice($map[$y], $x );
    if ($map[$y][$x] == max($leftPart) && array_count_values($leftPart)[$map[$y][$x]] == 1 || $map[$y][$x] == max($rightPart) && array_count_values($rightPart)[$map[$y][$x]] == 1)
        return true;

    // Top and bottom
    $column = [];
    $bottomPart = [];
    foreach ($map as $row) {
        $column[] = $row[$x];
    }
    $topPort = array_slice($column, 0, $y+1);
    $bottomPart = array_slice($column, $y );
    if ($map[$y][$x] == max($topPort) && array_count_values($topPort)[$map[$y][$x]] == 1 || $map[$y][$x] == max($bottomPart) && array_count_values($bottomPart)[$map[$y][$x]] == 1)
        return true;

    return false;
}

function scenicScore(array &$map, int $x, int $y): int
{
    $score = 1;
    $val = $map[$y][$x];
    // Left
    $count = 0;
    for ($i = $x-1; $i >= 0; $i--) {
        $count++;
        if ($map[$y][$i] >= $val) {
            break;
        }
    }
    $score *= $count;

    // Right
    $count = 0;
    for ($i = $x+1; $i < count($map[$y]); $i++) {
        $count++;
        if ($map[$y][$i] >= $val) {
            break;
        }
    }
    $score *= $count;

    // Top
    $count = 0;
    for ($i = $y-1; $i >= 0; $i--) {
        $count++;
        if ($map[$i][$x] >= $val) {
            break;
        }
    }
    $score *= $count;

    // Bottom
    $count = 0;
    for ($i = $y+1; $i < count($map); $i++) {
        $count++;
        if ($map[$i][$x] >= $val) {
            break;
        }
    }
    $score *= $count;
    return $score;
}

function part1($input): int
{
    $visible = [];
    foreach ($input as $y => $row) {
        foreach ($row as $x => $value) {
            if (isVisibleFromOutside($input, $x, $y)) {
                if (!in_array($value, $visible)) {
//                    if ($x == 0 || $y == 0 || $x == count($row) - 1 || $y == count($input) - 1)
//                        continue;
                    $visible[] = "$value: ($x,$y)";
                }
            }
        }
    }
//    var_dump($visible);
    return count($visible);
}

function part2($input): int
{
    $scores = [];
    foreach ($input as $y => $row) {
        foreach ($row as $x => $value) {
            if (isVisibleFromOutside($input, $x, $y)) {
                $scores[] = scenicScore($input, $x, $y);
            }
        }
    }
    var_dump($scores);
    return max($scores);
}

$input = parse_input();
scenicScore($input, 2, 1);
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;