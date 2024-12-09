<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const BOX = '#';
const GUARD = '^';

const N = 'n';
const E = 'e';
const S = 's';
const W = 'w';

// guard position
$gx = -1;
$gy = -1;

// guard movement direction
$dx = 0;
$dy = 0;
$gDir = '';

$height = count($lines);
$width = strlen($lines[0]);

// number of spaces that have been visited
$count = 0;

$grid = [];
foreach($lines as $y => $l) {
	$split = str_split($l);

	foreach($split as $x => $s) {
		$grid[$y][$x] = [
			'type' => $s,
			'dir' => [],
		];

		if ($s === GUARD) {
			$gx = $x;
			$gy = $y;

			$dx = 0;
			$dy = -1;
			$gDir = N;

			// mark the initial cell direction
			$grid[$y][$x]['dir'][] = $gDir;
		}
	}
}

$ogGx = $gx;
$ogGy = $gy;

foreach($grid as $y => $row) {
	foreach($row as $x => $cell) {
		$temp = $grid;

		// guard cell can't become a box
		if ($temp[$y][$x]['type'] === GUARD) {
			continue;
		}

		$temp[$y][$x]['type'] = '#';

		// reset the guard
		$gx = $ogGx;
		$gy = $ogGy;

		$dx = 0;
		$dy = -1;
		$gDir = N;

		$loop = 0;
		while (isset($temp[$gy + $dy][$gx + $dx]) && $loop < 100000) {
			$loop++; // avoid infinite loop :)

			if ($temp[$gy + $dy][$gx + $dx]['type'] === BOX) {
				// rotate the guard
				// up would be y=-1, x=0, change to y=0, x=1
				// down is y=1, x=0, change to y=0, x=-1
				if ($dx === 0) {
					$dx = -1 * $dy;
					$dy = 0;

					$gDir = $gDir === N ? E : W;
				// right would be y=0, x=1, change to y=1, x=0
				// left is y=0, x=-1, change to y=-1, x=0
				} else if ($dy === 0) {
					$dy = $dx;
					$dx = 0;

					$gDir = $gDir === E ? S : N;
				}

				// add this dir to the current space
				$temp[$gy][$gx]['dir'][$gDir] = true;
			} else {
				// move the guard and mark the direction
				$gx += $dx;
				$gy += $dy;

				// if this direction has already been visited, we're in a loop
				if (isset($temp[$gy][$gx]['dir'][$gDir])) {
					$count++;
					break;
				}

				// mark which direction the guard moved
				$temp[$gy][$gx]['dir'][$gDir] = true;
			}
		}
	}
}

echo '<pre>';
var_dump($count);
echo '</pre>';
die();
