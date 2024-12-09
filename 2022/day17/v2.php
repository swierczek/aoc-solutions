<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const SEP = ',';
const ROCK = '#';
const FALLING = '@';
const AIR = '.';

const LEFT = '<';
const RIGHT = '>';

const STEPS = 1000000000000;
const START_X = 2;
const START_ADD_Y = 3;

const WIDTH = 7;

// x,y, bottom left to bottom right, then up a row
$rocks = [
	/*
		####
	*/
	[
		'width' => 4,
		'height' => 1,
		'positions' => [
			'0,0' => FALLING,
			'1,0' => FALLING,
			'2,0' => FALLING,
			'3,0' => FALLING,
		]
	],

	/*
		.#.
		###
		.#.
	*/
	[
		'width' => 3,
		'height' => 3,
		'positions' => [
			// '0,0' => AIR,
			'1,0' => FALLING,
			// '2,0' => AIR,
			'0,1' => FALLING,
			'1,1' => FALLING,
			'2,1' => FALLING,
			// '0,2' => AIR,
			'1,2' => FALLING,
			// '2,2' => AIR,
		]
	],

	/*
		..#
		..#
		###
	*/
	[
		'width' => 3,
		'height' => 3,
		'positions' => [
			'0,0' => FALLING,
			'1,0' => FALLING,
			'2,0' => FALLING,
			// '0,1' => AIR,
			// '1,1' => AIR,
			'2,1' => FALLING,
			// '0,2' => AIR,
			// '1,2' => AIR,
			'2,2' => FALLING,
		]
	],

	/*
		#
		#
		#
		#
	*/
	[
		'width' => 1,
		'height' => 4,
		'positions' => [
			'0,0' => FALLING,
			'0,1' => FALLING,
			'0,2' => FALLING,
			'0,3' => FALLING,
		]
	],

	/*
		##
		##
	*/
	[
		'width' => 2,
		'height' => 2,
		'positions' => [
			'0,0' => FALLING,
			'1,0' => FALLING,
			'0,1' => FALLING,
			'1,1' => FALLING,
		]
	],
];

$moves = str_split($lines[0]);

// keep track of rock coords?
$curr = [];
$prev = [];

$grid = [];
$gridHeight = 0;

$prevHeight = 0;

$currMove = 0;
$currRock = 0;

$cache = [];

for($i=0; $i<STEPS; $i++) {
	// echo "new rock\n";

	$rockNum = $currRock % count($rocks);
	$moveNum = $currMove % count($moves);

	if ($rockNum == 0) {
		// $cache[] = $prevHeight;
		// echo "$i, rock is 0, move $moveNum, height $gridHeight, change ".($gridHeight - $prevHeight)."\n";
	}

	// add rock, then push, then move down
	$rock = $rocks[$rockNum];

	// add rock
	$rockCoords = [];
	foreach($rock['positions'] as $coords => $p) {
		$xy = explode(SEP, $coords);
		$x = $xy[0] + START_X;
		$y = $xy[1] + START_ADD_Y + $gridHeight;
		setGrid($x, $y, FALLING);
		$rockCoords[] = $x.SEP.$y;
	}

	// printGrid();

	// now the grid should have the falling rock,
	// and we have a list of the rock coords to iterate over

	$falling = true;
	while ($falling) {
		$moveIndex = $currMove % count($moves);
		$move = $moves[$moveIndex];

		// try to move it left or right
		if ($move == LEFT) {
			$moved = moveLeft($rockCoords);
		} else if ($move == RIGHT) {
			$moved = moveRight($rockCoords);
		} else {
			// echo "cannot move $move\n";
		}

		// printGrid();

		$currMove++;

		// then try to move it down
		$falling = moveDown($rockCoords, $rock);

		// printGrid();
	}

	// echo "not falling\n";

	if (!$falling) {
		$currRock++;

		if ($rockNum == 0) {
			$prevHeight = $gridHeight;
		}

		$gridHeight = max($gridHeight, explode(SEP, $rockCoords[0])[1] + $rock['height']);
		// echo "setting new height to $gridHeight\n";

		// no longer falling, mark it as a rock
		foreach($rockCoords as $c) {
			setGrid($c, ROCK);
		}
	}

	// printGrid();
}

echo '<pre>';
var_dump($gridHeight);
echo '</pre>';
die();


function setGrid($x, $y, $val = null) {
	global $grid;

	// accept setGrid('x,y', ROCK) too
	if ($val === null) {
		$grid[$x] = $y;
	} else {
		$grid[$x.SEP.$y] = $val;
	}
}

function checkGrid($x, $y = null) {
	global $grid;

	// accept checkGrid('x,y') too
	if ($y === null) {
		return $grid[$x] ?? AIR;
	} else {
		return $grid[$x.SEP.$y] ?? AIR;
	}
}



function moveLeft(&$coords) {
	global $grid;

	$newCoords = [];

	// check if it's already on the left edge, or if any of the rocks would intersect with the grid
	foreach($coords as $c) {
		$xy = explode(SEP, $c);
		$x = intval($xy[0]);
		$y = intval($xy[1]);

		$x--;

		$newCoords[] = $x.SEP.$y;

		if ($x < 0 || checkGrid($x, $y) === ROCK) {
			// echo "did not move left\n";
			return false;
		}
	}

	// if we're here, we are able to move left

	// set all current coords to air first
	foreach($coords as $c) {
		setGrid($c, AIR);
	}

	// set all new coords to falling
	foreach($newCoords as $c) {
		setGrid($c, FALLING);
	}

	// echo "moved left\n";

	$coords = $newCoords;

	return true;
}

function moveRight(&$coords) {
	global $grid;

	$newCoords = [];

	// check if it's already on the right edge, or if any of the rocks would intersect with the grid
	foreach($coords as $c) {
		$xy = explode(SEP, $c);
		$x = intval($xy[0]);
		$y = intval($xy[1]);

		$x++;

		$newCoords[] = $x.SEP.$y;

		if ($x >= WIDTH || checkGrid($x, $y) === ROCK) {
			// echo "did not move right\n";
			return false;
		}
	}

	// if we're here, we are able to move right

	// set all current coords to air first
	foreach($coords as $c) {
		setGrid($c, AIR);
	}

	// set all new coords to falling
	foreach($newCoords as $c) {
		setGrid($c, FALLING);
	}

	// echo "moved right\n";

	$coords = $newCoords;

	return true;
}

function moveDown(&$coords) {
	global $grid;

	$newCoords = [];

	foreach($coords as $c) {
		$xy = explode(SEP, $c);
		$x = intval($xy[0]);
		$y = intval($xy[1]);

		$y--;

		$newCoords[] = $x.SEP.$y;

		if ($y < 0 || checkGrid($x, $y) === ROCK) {
			// echo "can not move down\n";
			return false;
		}
	}

	// if we're here, we are able to move down

	// set all current coords to air first
	foreach($coords as $c) {
		setGrid($c, AIR);
	}

	// set all new coords to falling
	foreach($newCoords as $c) {
		setGrid($c, FALLING);
	}

	// echo "moved down\n";

	$coords = $newCoords;

	return true;
}

function printGrid() {
	global $grid;
	global $gridHeight;

	for($y=$gridHeight+6; $y>=0; $y--) {
		for($x=0; $x<WIDTH; $x++) {
			echo $grid[$x.SEP.$y] ?? '.';
		}

		echo "\n";
	}

	echo "\n";
}