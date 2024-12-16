<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const WALL = '#';
const SPACE = '.';
const BOX = 'O';
const ROBOT = '@';

const LEFT = '<';
const RIGHT = '>';
const UP = '^';
const DOWN = 'v';

$ry = -1;
$rx = -1;

$steps = '';
$grid = [];
foreach($lines as $y => $l) {
	if (stripos($l, '#') === 0) {
		$split = str_split($l);

		foreach($split as $x => $cell) {
			$grid[$y][$x] = [
				'y' => $y,
				'x' => $x,
				'val' => $cell,
			];

			if ($cell === ROBOT) {
				$ry = $y;
				$rx = $x;
			}
		}
	} else {
		$steps .= $l;
	}
}

$steps = str_split($steps);

foreach($steps as $count => $s) {
	// printGrid();

	// echo "checking step # $count, $s\n";

	$dy = 0;
	$dx = 0;

	if ($s === UP) {
		$dy = -1;
	} else if ($s === RIGHT) {
		$dx = 1;
	} else if ($s === DOWN) {
		$dy = 1;
	} else if ($s === LEFT) {
		$dx = -1;
	}

	move($dy, $dx);
}

// printGrid();

$score = calculateGPS();

echo '<pre>';
var_dump($score);
echo '</pre>';
die();

function move($y, $x) {
	global $grid;
	global $ry;
	global $rx;

	$num = canMoveNum($y, $x);

	// hit a wall, don't do anything
	if ($num === false) {
		// echo "wall\n";
		return;
	}

	// echo "can move $num\n";

	// make current robot a space
	$grid[$ry][$rx]['val'] = SPACE;
	// make next space the robot
	$grid[$ry+$y][$rx+$x]['val'] = ROBOT;

	// make last space a box
	if ($num > 1) {
		$grid[$ry+($num*$y)][$rx+($num*$x)]['val'] = BOX;
	}

	$ry += $y;
	$rx += $x;
}

function printGrid() {
	global $grid;

	foreach($grid as $y => $row) {
		foreach($row as $x => $cell) {
			echo $cell['val'];
		}

		echo "\n";
	}

	echo "\n";
}

function canMoveNum($y, $x) {
	global $grid;
	global $ry;
	global $rx;

	$tempY = $ry;
	$tempX = $rx;

	$count = 1;
	while ($next = @$grid[$tempY+$y][$tempX+$x]['val']) {
		if ($next === BOX) {
			// continue
			$count++;
			$tempY += $y;
			$tempX += $x;
		} else if ($next === SPACE) {
			return $count;
		} else if ($next === WALL) {
			return false;
		}
	}

	var_dump('what');
	die();
}

function calculateGPS() {
	global $grid;

	$score = 0;
	foreach($grid as $y => $row) {
		foreach($row as $x => $cell) {
			if ($cell['val'] !== BOX) {
				continue;
			}

			$score += (100 * $y) + $x;
		}
	}

	return $score;
}