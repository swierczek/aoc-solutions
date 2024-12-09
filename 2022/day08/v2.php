<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$grid = matrix_to_array($input);
$maxXIndex = count($grid[0]) - 1;
$maxYIndex = count($grid) - 1;

$maxScore = -1;
for ($y = 0; $y <= $maxYIndex; $y++) {
	for ($x = 0; $x <= $maxXIndex; $x++) {
		$score = checkScore($grid, $x, $y);

		if ($score > $maxScore) {
			$maxScore = $score;
		}
	}
}

echo '<pre>';
var_dump($maxScore);
echo '</pre>';
die();

// https://stackoverflow.com/questions/10693490/php-convert-string-matrix-into-multi-dimensional-array
function matrix_to_array($matrix) {
	$matrix = explode("\n", $matrix);
	$matrix = array_map("str_split", $matrix);
	return $matrix;
}

function checkScore(array $grid, int $xCoord, int $yCoord) {
	global $maxXIndex;
	global $maxYIndex;

	$currTree = $grid[$yCoord][$xCoord];

	$countLeft = 0;
	$countRight = 0;
	$countUp = 0;
	$countDown = 0;

	// check left
	for ($x = $xCoord-1; $x >= 0; $x--) {
		$countLeft++;
		if ($grid[$yCoord][$x] >= $currTree) {
			break;
		}
	}

	// check right
	for ($x = $xCoord+1; $x <= $maxXIndex; $x++) {
		$countRight++;
		if ($grid[$yCoord][$x] >= $currTree) {
			break;
		}
	}

	// check up
	for ($y = $yCoord-1; $y >= 0; $y--) {
		$countUp++;
		if ($grid[$y][$xCoord] >= $currTree) {
			break;
		}
	}

	// check down
	for ($y = $yCoord+1; $y <= $maxYIndex; $y++) {
		$countDown++;
		if ($grid[$y][$xCoord] >= $currTree) {
			break;
		}
	}

	return $countUp * $countLeft * $countRight * $countDown;
}