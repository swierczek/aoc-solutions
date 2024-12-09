<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$oct = array_map('trim', explode("\n", $input));

foreach($oct as $key => $l) {
	$oct[$key] = str_split($l);
}

$visited = [];
$flashed = [];
$anyFlashed = false;
$loop = 0;
$toFlash = [];
$numFlashed = 0;

// assuming this will eventually "break"...
$i=1;
while(true) {
	$visited = [];
	$flashed = [];
	$toFlash = [];

	$anyFlashed = false;

	// increase all oct energy by 1
	for($x=0; $x<count($oct); $x++) {
		for($y=0; $y<count($oct[$x]); $y++) {
			$oct[$x][$y]++;

			if ($oct[$x][$y] > 9) {
				array_push($toFlash, ['x' => $x, 'y' => $y]);
			}
		}
	}

	while (count($toFlash) > 0) {
		$curr = array_pop($toFlash);
		flash($curr['x'], $curr['y']);
	}

	// reset all that flashed (greater than 9)
	for($x=0; $x<count($oct); $x++) {
		for($y=0; $y<count($oct[$x]); $y++) {
			if ($oct[$x][$y] > 9) {
				$oct[$x][$y] = 0;
			}
		}
	}

	if (count($flashed) > 0) {
		echo count($flashed)." flashed on step $i".PHP_EOL;

		if (count($flashed) == 100) {
			break;
		}
	}

	$i++;
}

die();



// true if the 4 adjacent cells are all higher than this one
function isLowest($x, $y) {
	global $lines;

	$val = $lines[$x][$y];

	$up = getVal($x, $y-1);
	$down = getVal($x, $y+1);
	$left = getVal($x-1, $y);
	$right = getVal($x+1, $y);

	$isLowest = $up > $val
		&& $down > $val
		&& $left > $val
		&& $right > $val;

	return $isLowest;
}

function flash($x, $y) {
	global $oct;
	global $flashed;
	global $anyFlashed;
	global $numFlashed;

	// out of range
	if (!isset($oct[$x][$y])) {
		return false;
	}

	// if it's not enough to flash yet or it's already flashed in this step
	if (isset($oct[$x][$y]) && $oct[$x][$y] <= 9) {
		// echo "$x, $y not flashy enough".PHP_EOL;
		return false;
	}

	if (isset($flashed['f'.$x.$y]) && $flashed['f'.$x.$y] === true) {
		// echo "$x, $y already flashed".PHP_EOL;
		return false;
	}

	// echo "flashing $x, $y".PHP_EOL;

	// mark this one as flashed this step
	$flashed['f'.$x.$y] = true;

	// mark any as flashed so we keep looping
	$anyFlashed = true;
	$numFlashed++;

	// check the 8 directions
	increaseAll($x, $y);

	// if ($oct[$x][$y] > 9) {
	// 	return ['x' => $x, 'y' => $y];
	// } else {
	// 	return false;
	// }
}

function increaseAll($x, $y) {
	global $oct;

	increase($x-1, $y-1);
	increase($x  , $y-1);
	increase($x+1, $y-1);
	increase($x-1, $y+1);
	increase($x  , $y+1);
	increase($x+1, $y+1);
	increase($x-1, $y  );
	increase($x+1, $y  );
}

function increase($x, $y) {
	global $toFlash;
	global $oct;

	if (isset($oct[$x][$y])) {
		$oct[$x][$y]++;

		// echo "$x, $y increased to ".$oct[$x][$y].PHP_EOL;

		if ($oct[$x][$y] > 9) {
			// echo "$x, $y is going to flash".PHP_EOL;
			array_push($toFlash, ['x' => $x, 'y' => $y]);
		}
	}
}

function printGrid() {
	global $oct;

	for($x=0; $x<count($oct); $x++) {
		for($y=0; $y<count($oct[$x]); $y++) {
			echo str_pad($oct[$x][$y], 3);
		}
		echo PHP_EOL;
	}

	echo PHP_EOL;
}