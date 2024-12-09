<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$grid = matrix_to_array($input);
$maxXIndex = count($grid[0]) - 1;
$maxYIndex = count($grid) - 1;

// only mark visible trees once (i.e. $visibleTrees[$x.','.$y])
$visibleTrees = [];

for ($y = 0; $y <= $maxYIndex; $y++) {

	// check the row left to right
	$maxX = -1;
	for ($x = 0; $x <= $maxXIndex; $x++) {
		$val = $grid[$y][$x];

		if ($val > $maxX) {
			$visibleTrees[$x.','.$y] = $val;
			$maxX = $val;
		}
	}

	// and again right to left
	$maxX = -1;
	for ($x = $maxXIndex; $x >= 0; $x--) {
		$val = $grid[$y][$x];

		if ($val > $maxX) {
			$visibleTrees[$x.','.$y] = $val;
			$maxX = $val;
		}
	}
}

// check columns
for ($x = 0; $x <= $maxXIndex; $x++) {

	// check the row top to bottom
	$maxY = -1;
	for ($y = 0; $y <= $maxYIndex; $y++) {
		$val = $grid[$y][$x];

		if ($val > $maxY) {
			$visibleTrees[$x.','.$y] = $val;
			$maxY = $val;
		}
	}

	// and again bottom to top
	$maxY = -1;
	for ($y = $maxYIndex; $y >= 0; $y--) {
		$val = $grid[$y][$x];

		if ($val > $maxY) {
			$visibleTrees[$x.','.$y] = $val;
			$maxY = $val;
		}
	}
}

echo '<pre>';
var_dump(count($visibleTrees));
echo '</pre>';
die();


// https://stackoverflow.com/questions/10693490/php-convert-string-matrix-into-multi-dimensional-array
function matrix_to_array($matrix) {
	$matrix = explode("\n", $matrix);
	$matrix = array_map("str_split", $matrix);
	return $matrix;
}
