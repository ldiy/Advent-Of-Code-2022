<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    $input =  file_get_contents($filename);
    $input = str_replace("]\r\n", "], ", $input);
    $input[strlen($input) - 1] = ']';
    print_r($input);
    eval('$input = [' .  $input . '];');

    return $input;
}

function compare($left, $right) {
    if (is_numeric($left) && is_numeric($right)) {
        if ($left < $right) return "ok";
        if ($left > $right) return "nok";
        return "con";
    }

    if (is_array($left) && is_array($right)) {
        $min = min(count($left), count($right));

        for ($i = 0; $i < $min; $i++) {
            $res = compare($left[$i], $right[$i]);
            if ($res != "con") return $res;
        }

        if (count($left) > count($right))
            return "nok";
        if (count($left) < count($right))
            return "ok";
        return "con";
    }

    if (is_numeric($left))
        return compare([$left], $right);

    if (is_numeric($right))
        return compare($left, [$right]);

    return "con";
}

function part1($input): int
{
    $result = 0;
    for($i = 0; $i < count($input); $i++) {
        $left = $input[$i];
        $right = $input[$i+1];

        $res = compare($left, $right);
        if ($res == "ok") $result += $i/2 + 1;

        $i++;
    }
    return $result;
}

function part2($input): int
{
    array_push($input, [[2]] , [[6]]);
    usort($input, function($left, $right) {
        return compare($left, $right) == "ok" ? -1 : 1;
    });

    $index2 = array_search([[2]], $input) + 1;
    $index6 = array_search([[6]], $input)  +1;
    return $index6 * $index2;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;