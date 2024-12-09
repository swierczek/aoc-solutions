<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('MIRROR_1', "\\");
define('MIRROR_2', "/");
define('SPLIT_H', "-");
define('SPLIT_V', "|");

define('L', 'left');
define('R', 'right');
define('U', 'up');
define('D', 'down');

define('X', 'x');
define('Y', 'y');
define('DIR', 'direction');

$map = [];
foreach($lines as $y => $l) {
	$split = str_split($l);

	foreach($split as $x => $val) {
		$map[$y][$x] = [
			'visited' => false,
			'val' => $val,
			'visited_dir' => [],

		];
	}
}

// start with one beam going right in the top left corner
// $beams = [
// 	[
// 		X => -1,
// 		Y => 0,
// 		DIR => R,
// 	]
// ];

$startingBeams = [];
for($y=0; $y<count($map); $y++) {
	// left edge
	$startingBeams[] = [
		X => -1,
		Y => $y,
		DIR => R,
	];
	// right edge
	$startingBeams[] = [
		X => count($map[0]),
		Y => $y,
		DIR => L,
	];
}
for($x=0; $x<count($map[0]); $x++) {
	// top edge
	$startingBeams[] = [
		X => $x,
		Y => -1,
		DIR => D,
	];
	// bottom edge
	$startingBeams[] = [
		X => $x,
		Y => count($map),
		DIR => U,
	];
}

$numEnergized = [];
foreach($startingBeams as $temp) {
	$beams = [
		$temp
	];

	$energized = [];
	$tempMap = $map;
	while (count($beams) > 0) {
		// echo count($beams) . " beams\n";

		foreach($beams as $key => $b) {
			$dir = $beams[$key][DIR];

			// move the beam
			if ($dir === U) {
				$beams[$key][Y] -= 1;
				// echo "  moving beam up\n";
			} else if ($dir === R) {
				$beams[$key][X] += 1;
				// echo "  moving beam right\n";
			} else if ($dir === D) {
				$beams[$key][Y] += 1;
				// echo "  moving beam down\n";
			} else if ($dir === L) {
				$beams[$key][X] -= 1;
				// echo "  moving beam left\n";
			}

			$x = $beams[$key][X];
			$y = $beams[$key][Y];

			$curr = &$tempMap[$y][$x] ?? null;

			// if we've hit the edge, remove the beam
			if ($curr === null) {
				// echo "    hit edge, removing beam\n";
				unset($beams[$key]);
				continue;
			}

			// echo "    current cell at $x, $y: {$curr['val']}\n";

			$curr['visited'] = true;
			$energized[$x.','.$y] = true;
			if (isset($curr['visited_dir'][$dir])) {
				// we've already been on this path, remove the beam to avoid infinite loops
				// echo "    cell already visited in this direction, removing beam\n";
				unset($beams[$key]);
				continue;
			} else {
				// echo "    marking space as visited going $dir\n";
				$curr['visited_dir'][$dir] = true;
			}



			$cell = $curr['val'];
			// determine what the beam does next
			if ($dir === U) {
				if ($cell === MIRROR_1) {
					$beams[$key][DIR] = L;
				} else if ($cell === MIRROR_2) {
					$beams[$key][DIR] = R;
				} else if ($cell === SPLIT_H) {
					// destroy this beam, add 2 others
					unset($beams[$key]);
					$beams[] = [
						X => $x,
						Y => $y,
						DIR => L,
					];
					$beams[] = [
						X => $x,
						Y => $y,
						DIR => R,
					];
				}
			} else if ($dir === R) {
				if ($cell === MIRROR_1) {
					$beams[$key][DIR] = D;
				} else if ($cell === MIRROR_2) {
					$beams[$key][DIR] = U;
				} else if ($cell === SPLIT_V) {
					// destroy this beam, add 2 others
					unset($beams[$key]);
					$beams[] = [
						X => $x,
						Y => $y,
						DIR => U,
					];
					$beams[] = [
						X => $x,
						Y => $y,
						DIR => D,
					];
				}
			} else if ($dir === D) {
				if ($cell === MIRROR_1) {
					$beams[$key][DIR] = R;
				} else if ($cell === MIRROR_2) {
					$beams[$key][DIR] = L;
				} else if ($cell === SPLIT_H) {
					// destroy this beam, add 2 others
					unset($beams[$key]);
					$beams[] = [
						X => $x,
						Y => $y,
						DIR => L,
					];
					$beams[] = [
						X => $x,
						Y => $y,
						DIR => R,
					];
				}
			} else if ($dir === L) {
				if ($cell === MIRROR_1) {
					$beams[$key][DIR] = U;
				} else if ($cell === MIRROR_2) {
					$beams[$key][DIR] = D;
				} else if ($cell === SPLIT_V) {
					// destroy this beam, add 2 others
					unset($beams[$key]);
					$beams[] = [
						X => $x,
						Y => $y,
						DIR => U,
					];
					$beams[] = [
						X => $x,
						Y => $y,
						DIR => D,
					];
				}
			}
		}
	}

	$numEnergized[] = count($energized);
}

echo '<pre>';
var_dump(max($numEnergized));
echo '</pre>';
die();
