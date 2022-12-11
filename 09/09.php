<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    $lines = lines($filename);
    $result = [];

    foreach ($lines as $line) {
        $result[] = explode(' ', $line);
    }

    return $result;
}

function nodePos($hPos, $tPos): array
{
    $dx = $hPos[0] - $tPos[0];
    $dy = $hPos[1] - $tPos[1];

    if (abs($dx) + abs($dy) <= 1 || abs($dx) == 1 && 1 == abs($dy)) {
        return [$tPos[0], $tPos[1]];
    }

    return [$tPos[0] + ($dx <=> 0), $tPos[1] + ($dy <=> 0)];
}

function part1($input): int
{
    $visited = ['0,0' => true];

    $headX = 0;
    $headY = 0;
    $tailX = 0;
    $tailY = 0;

    foreach ($input as $line) {
        $direction = $line[0];
        $distance = intval($line[1]);

        $Hdx = 0;
        $Hdy = 0;
        switch ($direction) {
            case 'U':
                $Hdy = 1;
                break;
            case 'D':
                $Hdy = -1;
                break;
            case 'L':
                $Hdx = -1;
                break;
            case 'R':
                $Hdx = 1;
                break;
        }

        for ($i = 0; $i < $distance; $i++) {
            $headX += $Hdx;
            $headY += $Hdy;

            $dx = $headX - $tailX;
            $dy = $headY - $tailY;

            if (abs($dx) + abs($dy) <= 1 || abs($dx) == abs($dy))
                continue;

            $tailX += $dx <=> 0;
            $tailY += $dy <=> 0;

            $visited["$tailX,$tailY"] = true;
        }
    }

    return count($visited);
}

function part2($input): int
{
    $visited = ['0,0' => 0];
    $positions = [
        0 => [0, 0], // HEAD
        1 => [0,0],
        2 => [0,0],
        3 => [0,0],
        4 => [0,0],
        5 => [0,0],
        6 => [0,0],
        7 => [0,0],
        8 => [0,0],
        9 => [0,0], // TAIL
    ];

    foreach ($input as $line) {
        $direction = $line[0];
        $distance = intval($line[1]);

        $Hdx = 0;
        $Hdy = 0;
        switch ($direction) {
            case 'U':
                $Hdy = 1;
                break;
            case 'D':
                $Hdy = -1;
                break;
            case 'L':
                $Hdx = -1;
                break;
            case 'R':
                $Hdx = 1;
                break;
        }

        for ($i = 0; $i < $distance; $i++) {
            $positions[0][0] += $Hdx;
            $positions[0][1] += $Hdy;

            for ($j = 1; $j <= 9; $j++) {
                $positions[$j] = nodePos($positions[$j-1], $positions[$j]);
            }
            $visited["{$positions[9][0]},{$positions[9][1]}"] = true;
        }
    }

    return count($visited);
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;