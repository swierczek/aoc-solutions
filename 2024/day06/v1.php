<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const BOX = '#';
const GUARD = '^';

// guard position
$gx = -1;
$gy = -1;

// guard movement direction
$dx = 0;
$dy = 0;

$height = count($lines);
$width = strlen($lines[0]);

$grid = [];
foreach($lines as $y => $l) {
	$split = str_split($l);

	foreach($split as $x => $s) {
		$grid[$y][$x] = [
			'type' => $s,
			'visited' => false,
			// if a pair exists in here from the current position, we're in a loop...
			// TODO: idk if we care about this at this point
			// 'visited_dir' => [], // x/y pairs of the direction we visited from
		];

		if ($s === GUARD) {
			$gx = $x;
			$gy = $y;

			$grid[$y][$x]['visited'] = true;

			echo "guard starting at $y, $x\n";

			$dx = 0;
			$dy = -1;
		}
	}
}

$loop = 0;
$count = 0;
while ($gx > 0 && $gx < $width && $gy > 0 && $gy < $height && $loop < 50) {
	if ($grid[$gy + $dy][$gx + $dx]['type'] === BOX) {
		// rotate the guard

		// up would be y=-1, x=0, change to y=0, x=1
		// down is y=1, x=0, change to y=0, x=-1
		if ($dx === 0) {
			$dx = -1 * $dy;
			$dy = 0;
		// right would be y=0, x=1, change to y=1, x=0
		// left is y=0, x=-1, change to y=-1, x=0
		} else if ($dy === 0) {
			$dy = $dx;
			$dx = 0;
		}
	} else {
		// move the guard and mark the new space as visited?
		$gx += $dx;
		$gy += $dy;

		echo "guard moved to $gy, $gx\n";

		if (!$grid[$gy][$gx]['visited']) {
			$count++;
			$grid[$gy][$gx]['visited'] = true;
		}
	}
}

echo '<pre>';
var_dump($count);
echo '</pre>';
die();
