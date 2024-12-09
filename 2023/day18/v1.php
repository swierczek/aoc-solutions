<?php

ini_set('memory_limit','2048M');
// ini_set('memory_limit','4096M');

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('R', 'R');
define('L', 'L');
define('U', 'U');
define('D', 'D');

define('TRENCH', '#');
// define('TRENCH2', "HERE\n\n\nHERE");
define('NOTHING', '.');

$steps = [];
$maxLeft = 0;
$maxRight = 0;
$maxUp = 0;
$maxDown = 0;

foreach($lines as $l) {
	$split = explode(' ', $l);

	$dir = $split[0];
	$num = intval($split[1]);


	$steps[] = [
		'dir' => $dir,
		'steps' => $num,
		'color' => str_replace('()', '', $split[2]),
	];

	if ($dir === R) {
		$maxRight += $num;
	} else if ($dir === L) {
		$maxLeft += $num;
	} else if ($dir === U) {
		$maxUp += $num;
	} else if ($dir === D) {
		$maxDown += $num;
	}
}

// lazy, but it might work
$map = array_fill(-$maxRight, $maxRight*2+1, []);

foreach($map as $key => $val) {
	$map[$key] = array_fill(-$maxDown, $maxDown*2+1, NOTHING);
}

// $map = [];
$x = 0;
$y = 0;
foreach($steps as $s) {
	$mult = 1;
	$dir = $s['dir'];
	$steps = $s['steps'];

	if ($dir === 'L' || $dir === 'U') {
		$mult = -1;
	}

	for ($i=0; $i<$steps; $i++) {
		if ($dir === R || $dir === L) {
			$x += $mult;
		} else if ($dir === U || $dir === D) {
			$y += $mult;
		}

		// $map[$y][$x] = [
		// 	'char' => TRENCH,
		// 	'color' => $s['color'],
		// ];

		$map[$y][$x] = TRENCH;
	}
}

// printGrid($map);

var_dump(count($map));

// delete empty rows
foreach($map as $y => $thing) {
	if (!isset(array_count_values($thing)[TRENCH])) {
		unset($map[$y]);
	}
}

// delete empty columns
$indexes = array_keys($map[0]);
$delete = [];
for($i=$indexes[0]; $i<$indexes[count($indexes)-1]; $i++) {
	if (!isset(array_count_values(array_column($map, $i))[TRENCH])) {
		$delete[] = $i;
	}
}

foreach($delete as $d) {
	foreach($map as $y => $thing) {
		unset($map[$y][$d]);
	}
}

// start at 363, 4 and fill the grid (minut index offsets)
// lazy way to determine a starting point to fill
fillGrid($map, [-198 + 353, -122 + 6]);

$trenchCount = 0;
foreach($map as $y => $thing) {
	foreach($thing as $x => $val) {
		if ($val === TRENCH) {
			$trenchCount++;
		}
	}
}

echo '<pre>';
var_dump($trenchCount);
echo '</pre>';
die();


function printGrid($grid)
{
	foreach($grid as $y => $thing) {
		foreach ($thing as $x => $val) {
			if (is_array($val) && isset($val['char'])) {
				echo $val['char'];
			} else {
				echo $val;
			}
		}

		echo "\n";
	}

	echo "\n";
}

function fillGrid(&$map, $start) {
	$q = [
		$start
	];

	$count = 0;
	while (count($q) > 0) {
		$cell = array_pop($q);

		// add 4 surrounding cells, and mark them as #?
		$x = $cell[0];
		$y = $cell[1];

		if (@$map[$y][$x-1] === NOTHING) {
			// echo "left is nothing, marking as trench\n";
			$map[$y][$x-1] = TRENCH;
			$q[] = [$x-1, $y];
		}

		if (@$map[$y][$x+1] === NOTHING) {
			// echo "right is nothing, marking as trench\n";
			$map[$y][$x+1] = TRENCH;
			$q[] = [$x+1, $y];
		}

		if (@$map[$y-1][$x] === NOTHING) {
			// echo "up is nothing, marking as trench\n";
			$map[$y-1][$x] = TRENCH;
			$q[] = [$x, $y-1];
		}

		if (@$map[$y+1][$x] === NOTHING) {
			// echo "down is nothing, marking as trench\n";
			$map[$y+1][$x] = TRENCH;
			$q[] = [$x, $y+1];
		}

		$count++;
	}
}