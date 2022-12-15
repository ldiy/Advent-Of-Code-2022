<?php
ini_set('memory_limit', '2G');
include_once "../tools.php";

const TEST = false;
//const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    $lines =  lines($filename);
    $result = [];

    foreach ($lines as $line) {
        $matches = [];
        preg_match("/Sensor at x=(?<x>-?\d+), y=(?<y>-?\d+): closest beacon is at x=(?<bx>-?\d+), y=(?<by>-?\d+)/", $line, $matches);
        $result[] = [
            "x" => (int)$matches["x"],
            "y" => (int)$matches["y"],
            "bx" => (int)$matches["bx"],
            "by" => (int)$matches["by"],
        ];
    }
    return $result;
}

function part1($input): int
{
    $row = 2000000;
    if (TEST)
        $row = 10;

    $row_grid = [];
    foreach ($input as $sensor) {
        if ($sensor['y'] == $row) {
            $row_grid[$sensor['x']] = 'S';
        }

        if ($sensor['by'] == $row) {
            $row_grid[$sensor['bx']] = 'B';
        }

        $distance = abs($sensor['x'] - $sensor['bx']) + abs($sensor['y'] - $sensor['by']);
        if ($sensor['y'] + $distance >= $row && $sensor['y'] - $distance <= $row) {
            $in_reach = $distance - abs($sensor['y'] - $row);
            for ($i = $sensor['x'] - $in_reach; $i <= $sensor['x'] + $in_reach; $i++) {
                if (!isset($row_grid[$i])) {
                    $row_grid[$i] = '#';
                }
            }
        }
    }
    return array_count_values($row_grid)['#'];
}

function overlap($sensor, $x, $y): bool
{
    $distance = abs($sensor['x'] - $x) + abs($sensor['y'] - $y);
    return $distance <= $sensor['r'];
}

function part2($input): int
{
    $min = 0;
    $max = 4000000;

    if (TEST)
        $max = 20;

    $x_cord = 0;
    $y_cord = 0;

    // Foreach sensor calculates its radius
    foreach ($input as $k => $sensor) {
        $input[$k]['r'] = abs($sensor['x'] - $sensor['bx']) + abs($sensor['y'] - $sensor['by']);
    }

    // For each sensor get the points on the border that do not overlap with other sensors
    foreach ($input as $s1=>$sensor) {
        $r = $sensor['r'] + 1;
        for ($x = $sensor['x'] - $r; $x <= $sensor['x'] + $r; $x++) {
            if ($x < $min || $x > $max)
                continue;
            $y = $sensor['y'] - $r + abs($sensor['x'] - $x);

            if (!($y < $min) && !($y > $max)) {

                $no_overlap = true;
                foreach ($input as $s2 => $other_sensor) {
                    if ($s1 == $s2)
                        continue;
                    if (overlap($other_sensor, $x, $y)) {
                        $no_overlap = false;
                    }
                }

                if ($no_overlap) {
                    $x_cord = $x;
                    $y_cord = $y;
                    break 2;
                }
            }

            $y = $sensor['y'] + $r - abs($sensor['x'] - $x);

            if (!($y < $min) && !($y > $max)) {
                $no_overlap = true;
                foreach ($input as $s2 => $other_sensor) {
                    if ($s1 == $s2)
                        continue;
                    if (overlap($other_sensor, $x, $y)) {
                        $no_overlap = false;
                    }
                }

                if ($no_overlap) {
                    $x_cord = $x;
                    $y_cord = $y;
                    break 2;
                }
            }
        }
    }

    return $x_cord * 4000000 + $y_cord;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;