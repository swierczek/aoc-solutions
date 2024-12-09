<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('SPACE', '.');
define('GALAXY', '#');
// define('EXPAND_DISTANCE', 100);
define('EXPAND_DISTANCE', 1000000);

$space = [];
$galaxies = [];
$rows = [];
$cols = [];

foreach($lines as $y => $l) {
	$l = str_split($l);
	$space[$y] = $l;

	foreach($l as $x => $cell) {
		if ($cell === GALAXY) {
			$galaxies[] = [
				'x' => $x,
				'y' => $y,
			];
		}
	}

	if (array_count_values($l)[SPACE] === count($l)) {
		$rows[$y] = $y;
	}
}

for($i=0; $i<count($space[0]); $i++) {
	$col = array_column($space, $i);

	if (array_count_values($col)[SPACE] === count($col)) {
		$cols[$i] = $i;
	}
}

$pairs = getPairs($galaxies);

$distances = getDistances($pairs, $galaxies, $rows, $cols);

echo '<pre>';
// var_dump($distances);
var_dump(array_sum($distances));
echo '</pre>';
die();



function getPairs(array $galaxies): array
{
	$pairs = [];
	for($i=0; $i<count($galaxies); $i++) {
		for($j=$i+1; $j<count($galaxies); $j++) {
			$pairs[] = [
				'a' => $i,
				'b' => $j,
			];
		}
	}

	return $pairs;
}

function getDistances(array $pairs, array $g, array $r, array $c): array
{
	$distances = [];

	foreach($pairs as $count => $pair) {
		$a = $pair['a'];
		$b = $pair['b'];

		// x1 < x2; y1 < y2
		$x1 = min($g[$a]['x'], $g[$b]['x']);
		$x2 = max($g[$a]['x'], $g[$b]['x']);
		$y1 = min($g[$a]['y'], $g[$b]['y']);
		$y2 = max($g[$a]['y'], $g[$b]['y']);

		// account for expanded rows
		$expandedRows = [];
		foreach($r as $row) {
			if ($y1 < $row && $row < $y2) {
				$expandedRows[] = $row;
			}
		}

		// account for expanded cols
		$expandedCols = [];
		foreach($c as $col) {
			if ($x1 < $col && $col < $x2) {
				$expandedCols[] = $col;
			}
		}

		$distance = abs($x2 - $x1) + abs($y2 - $y1)
			+ (count($expandedRows) * (EXPAND_DISTANCE -1))
			+ (count($expandedCols) * (EXPAND_DISTANCE -1));

		// echo "Distance between $a and $b is $distance\n";

		$distances[$a.','.$b] = $distance;
	}

	return $distances;
}