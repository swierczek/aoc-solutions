<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const NODE_SEP = ' -> ';
const COORD_SEP = ',';
const STONE = '#';
const AIR = '.';
const SAND = 'O';

const START_X = 500;
const START_Y = 0;

$floor = 0;

$grid = [];
foreach($lines as $line) {
	$nodes = explode(NODE_SEP, $line);

	for($i=0; $i<count($nodes)-1; $i++) {
		$curr = (object) [
			'x' => 0,
			'y' => 0,
		];
		$next = (object) [
			'x' => 0,
			'y' => 0,
		];

		list($curr->x, $curr->y) = explode(COORD_SEP, $nodes[$i]);
		list($next->x, $next->y) = explode(COORD_SEP, $nodes[$i+1]);

		if ($curr->x == $next->x) {
			// iterate over y
			$min = min($curr->y, $next->y);
			$max = max($curr->y, $next->y);

			for($y=$min; $y<=$max; $y++) {
				setGrid($curr->x, $y, STONE);
			}
		} else if ($curr->y == $next->y) {
			// iterate over x
			$min = min($curr->x, $next->x);
			$max = max($curr->x, $next->x);

			for($x=$min; $x<=$max; $x++) {
				setGrid($x, $curr->y, STONE);
			}

			$floor = max($floor, $curr->y, $next->y);
		}
	}
}

// set the floor 2 below the lowest stone
$floor += 2;

// hacky way first, just add a wide row of stones?
for ($x=0; $x<START_X*2; $x++) {
	setGrid($x, $floor, STONE);
}

// grid is configured with stones, now simulate the sand
$count = 0;
$moreSand = true;

$curr = (object) [
	'x' => 0,
	'y' => 0,
];
$prev = (object) [
	'x' => 0,
	'y' => 0,
];

while ($moreSand) {
	if (checkGrid(START_X, START_Y) != SAND) {
		$curr->x = START_X;
		$curr->y = START_Y;

		setGrid($curr->x, $curr->y, SAND);
	} else {
		// sand is filled to the top (ha, I totally knew that was going to happen)
		var_dump('sand is full');
		break;
	}

	$moved = true;
	while ($moved) {
		// save the prev coords
		$prev->x = $curr->x;
		$prev->y = $curr->y;

		// if the next col is not sand/stone, move there
		if (!in_array(checkGrid($curr->x, $curr->y+1), [STONE, SAND])) {
			$curr->x = $curr->x;
			$curr->y = $curr->y+1;
			// echo "sand moved down to $curr->x, $curr->y\n";
		} else if (!in_array(checkGrid($curr->x-1, $curr->y+1), [STONE, SAND])) {
			$curr->x = $curr->x-1;
			$curr->y = $curr->y+1;
			// echo "sand moved left to $curr->x, $curr->y\n";
		} else if (!in_array(checkGrid($curr->x+1, $curr->y+1), [STONE, SAND])) {
			$curr->x = $curr->x+1;
			$curr->y = $curr->y+1;
			// echo "sand moved right to $curr->x, $curr->y\n";
		} else {
			echo "sand came to rest at $curr->x, $curr->y\n";

			$count++;
			$moved = false;
		}

		if ($moved) {
			// move the sand from old to new
			setGrid($prev->x, $prev->y, AIR);
			setGrid($curr->x, $curr->y, SAND);
		}
	}
}

echo '<pre>';
var_dump($count);
echo '</pre>';
die();


function setGrid($x, $y, $val) {
	global $grid;
	$grid[$x.COORD_SEP.$y] = $val;
}

function checkGrid($x, $y) {
	global $grid;
	return $grid[$x.COORD_SEP.$y] ?? AIR;
}
