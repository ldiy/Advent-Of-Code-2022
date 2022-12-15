<?php
include_once "../tools.php";

//const TEST = false;
const TEST = true;

class Graph
{
    public Ds\Vector $nodes;
    public int $minDistance = PHP_INT_MAX;
    public string $name;
    public int $height;

    public function __construct(string $name, int $height = 0)
    {
        $this->height = $height;
        $this->name = $name;
        $this->nodes = new Ds\Vector();
    }

    public function addNode(Graph $node): void
    {
        if ($this->nodes->contains($node)) {
            return;
        }

        $this->nodes->push($node);
    }
}
function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    return array_map(function($line) { return str_split($line); }, lines($filename));
}

$nodes = [];

function add_adj(Graph $node, array &$grid, int $x, int $y): void
{
    global $nodes;
    $adjacent = [
        [$x, $y - 1],
        [$x, $y + 1],
        [$x - 1, $y],
        [$x + 1, $y],
    ];

    $val = $grid[$x][$y];
    if ($val == 'S') $val = 'a';

    foreach ($adjacent as $adj) {
        $adjX = $adj[0];
        $adjY = $adj[1];
        if (!isset($grid[$adjX]) || !isset($grid[$adjX][$adjY])) continue;

        $next_val = $grid[$adjX][$adjY];
        if ($next_val == 'E') $next_val = 'z';

        if (ord($next_val) - ord($val) <= 1) {
            if (array_key_exists("$adjX,$adjY", $nodes)) {
                $newNode = $nodes["$adjX,$adjY"];
                $node->addNode($newNode);
            } else {
                $newNode = new Graph("$adjX,$adjY", ord($next_val) - ord('a'));
                $nodes["$adjX,$adjY"] = $newNode;
                $node->addNode($newNode);
                add_adj($newNode, $grid, $adjX, $adjY);
            }
        }
    }
}

function grid_to_graph(array $grid): Graph
{
    global $nodes;

    // Find the starting point
    $startX = 0;
    $startY = 0;
    foreach ($grid as $x => $row) {
        foreach ($row as $y => $value) {
            if ($value == 'S') {
                $startX = $x;
                $startY = $y;
                break 2;
            }
        }
    }

    $nodes["$startX,$startY"] = new Graph("$startX,$startY", 0);
    add_adj($nodes["$startX,$startY"], $grid, $startX, $startY);

    return $nodes["$startX,$startY"];
}

function dijkstra_shortest_pad(string $start, string $end): int
{
    global $nodes;
    $endNode = $nodes[$end];

    $queue = new Ds\PriorityQueue();
    $queue->push($start, 0);
    $nodes[$start]->minDistance = 0;

    while (!$queue->isEmpty()) {
        $current = $queue->pop();
        $currentNode = $nodes[$current];
        foreach ($currentNode->nodes as $node) {
            $distance = $currentNode->minDistance + 1;
            if ($distance < $node->minDistance) {
                $node->minDistance = $distance;
                $queue->push($node->name, $distance);
            }
        }
    }

    return $endNode->minDistance;
}

function part1($grid): int
{
    global $nodes;
    $start  = grid_to_graph($grid);

    $end_x = 0;
    $end_y = 0;
    foreach ($grid as $x => $row) {
        foreach ($row as $y => $value) {
            if ($value == 'E') {
                $end_x = $x;
                $end_y = $y;
                break 2;
            }
        }
    }

    return dijkstra_shortest_pad($start->name, "$end_x,$end_y");
}

function part2($grid): int
{
    $start = grid_to_graph($grid);

    $start_points = [
        $start->name,
    ];

    foreach ($grid as $x => $row) {
        foreach ($row as $y => $value) {
            if ($value == 'a') {
                $start_points[] = "$x,$y";
            }
        }
    }

    $end_x = 0;
    $end_y = 0;
    foreach ($grid as $x => $row) {
        foreach ($row as $y => $value) {
            if ($value == 'E') {
                $end_x = $x;
                $end_y = $y;
                break 2;
            }
        }
    }

    $min = PHP_INT_MAX;
    foreach ($start_points as $start) {
        $min = min($min, dijkstra_shortest_pad($start, "$end_x,$end_y"));
    }
    return $min;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;;