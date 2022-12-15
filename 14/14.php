<?php
include_once "../tools.php";

//const TEST = false;
const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    $coords = [];
    $paths =  lines($filename);
    foreach ($paths as $path) {
        $points = explode(" -> ", $path);
        for($i=0; $i<count($points) - 1; $i++) {
            [$x1,$y1] = explode(",", $points[$i]);
            [$x2,$y2] = explode(",", $points[$i+1]);

            if ($x1 == $x2) {
                $y_min = min($y1, $y2);
                $y_max = max($y1, $y2);
                for($y=$y_min; $y<=$y_max; $y++) {
                    $coords[$x1][$y] = true;
                }
            } else {
                $x_min = min($x1, $x2);
                $x_max = max($x1, $x2);
                for($x=$x_min; $x<=$x_max; $x++) {
                    $coords[$x][$y1] = true;
                }
            }
        }
    }

    return $coords;
}

function print_map(&$coords, &$in_rest): void
{
    $x_min = min(array_keys($coords));
    $x_max = max(array_keys($coords));
    $y_min = min(array_map(function($x) { return min(array_keys($x)); }, $coords));
    $y_max = max(array_map(function($x) { return max(array_keys($x)); }, $coords));

    for($y=$y_min; $y<=$y_max; $y++) {
        for ($x = $x_min; $x <= $x_max; $x++) {
            if (isset($coords[$x][$y])) {
                    echo "#";
            } elseif (isset($in_rest[$x][$y])) {
                echo "O";
            } else {
                echo ".";
            }
        }
        echo PHP_EOL;
    }
}

function pos_in_coords($pos, &$coords, &$in_rest): bool
{
    return isset($coords[$pos[0]][$pos[1]]) || isset($in_rest[$pos[0]][$pos[1]]);
}

function part1($walls): int
{
    $start_point = [500,0];
    $in_rest = [];
    $result = 0;

    $min_x = 500;
    $max_x = 500;
    $min_y = 0;
    $max_y = 0;
    foreach ($walls as $x => $ys) {
        $min_x = min($min_x, $x);
        $max_x = max($max_x, $x);
        foreach ($ys as $y => $v) {
            $min_y = min($min_y, $y);
            $max_y = max($max_y, $y);
        }
    }

    while(true) {
        $prev_pos = $start_point;
//        print_map($walls, $in_rest);
        while(true){
            if ($prev_pos[0] < $min_x || $prev_pos[0] > $max_x || $prev_pos[1] > $max_y) {
                break 2;
            }

            $next_pos = [$prev_pos[0], $prev_pos[1]+1];
            if (!pos_in_coords($next_pos, $walls, $in_rest)) {
                $prev_pos = $next_pos;
                continue;
            }

            $next_pos = [$prev_pos[0]-1, $prev_pos[1]+1];
            if (!pos_in_coords($next_pos, $walls, $in_rest)) {
                $prev_pos = $next_pos;
                continue;
            }

            $next_pos = [$prev_pos[0]+1, $prev_pos[1]+1];
            if (!pos_in_coords($next_pos, $walls, $in_rest)) {
                $prev_pos = $next_pos;
                continue;
            }
            break;
        }
        $in_rest[$prev_pos[0]][$prev_pos[1]] = true;
        $result++;
    }
    return $result;
}

function pos_in_coords2($pos, &$coords, &$in_rest, $floor): bool
{
    return isset($coords[$pos[0]][$pos[1]]) || isset($in_rest[$pos[0]][$pos[1]]) || $pos[1] == $floor;
}

function part2($walls): int
{
    $start_point = [500,0];
    $in_rest = [];
    $result = 0;

    $min_x = 500;
    $max_x = 500;
    $min_y = 0;
    $max_y = 0;
    foreach ($walls as $x => $ys) {
        $min_x = min($min_x, $x);
        $max_x = max($max_x, $x);
        foreach ($ys as $y => $v) {
            $min_y = min($min_y, $y);
            $max_y = max($max_y, $y);
        }
    }

    $floor = $max_y + 2;

    while(true) {
        $prev_pos = $start_point;
//        print_map($walls, $in_rest);
        if (isset($in_rest[$start_point[0]][$start_point[1]])) {
            break;
        }
        while(true){
            $next_pos = [$prev_pos[0], $prev_pos[1]+1];
            if (!pos_in_coords2($next_pos, $walls, $in_rest, $floor)) {
                $prev_pos = $next_pos;
                continue;
            }

            $next_pos = [$prev_pos[0]-1, $prev_pos[1]+1];
            if (!pos_in_coords2($next_pos, $walls, $in_rest, $floor)) {
                $prev_pos = $next_pos;
                continue;
            }

            $next_pos = [$prev_pos[0]+1, $prev_pos[1]+1];
            if (!pos_in_coords2($next_pos, $walls, $in_rest, $floor)) {
                $prev_pos = $next_pos;
                continue;
            }
            break;
        }
        $in_rest[$prev_pos[0]][$prev_pos[1]] = true;
        $result++;
    }
    return $result;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
//echo "Part2: " . part2($input) . PHP_EOL;