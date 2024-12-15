<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');
$stepStart = intval($argv[2]);
$stepEnd = intval($argv[3]);

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('WIDTH', stripos($filename, '/input.txt') !== false ? 101 : 11);
define('HEIGHT', stripos($filename, '/input.txt') !== false ? 103 : 7);

$bots = [];
foreach($lines as $l) {
	preg_match('/^p=(\d+),(\d+) v=(-?\d+),(-?\d+)$/', $l, $matches);

	$bots[] = [
		'py' => intval($matches[2]),
		'px' => intval($matches[1]),
		'vy' => intval($matches[4]),
		'vx' => intval($matches[3]),
	];
}

for($step=$stepStart; $step<$stepEnd; $step+=101) {
	$ref = [];
	foreach($bots as $key => $b) {
		$newY = $b['py'] + (($b['vy'] * $step) % HEIGHT);
		$newX = $b['px'] + (($b['vx'] * $step) % WIDTH);

		if ($newY < 0) {
			$newY += HEIGHT;
		} else if ($newY > HEIGHT-1) {
			$newY = $newY % HEIGHT;
		}

		if ($newX < 0) {
			$newX += WIDTH;
		} else if ($newX > WIDTH-1) {
			$newX = $newX % WIDTH;
		}

		$ref[$newY."|".$newX] = true;
	}

	// print the grid
	echo "printing step $step\n\n";
	for($y=0; $y<HEIGHT; $y++) {
		for($x=0; $x<HEIGHT; $x++) {
			echo isset($ref[$y."|".$x]) ? 'X' : ' ';
		}
		echo "\n";
	}

	echo "\n";
}


/*
	interesting step #s:
	48
	104
	149
	207
	310
	351
	413
	452
	516
	553
	619
	654
	722
	755 |
	825 -
	856 |
	928 -
	957 |
	1031 -
	1058 |
	1134 -
	1159 |
	1260 |
	1361 |
	1462 |

	final command for the solution (step 7623):
	php v2.php input.txt 4997 12000 > output.txt

	Smaller version:
	php v2.php input.txt 7320 8000 > output.txt

	To see it directly:
	php v2.php input.txt 7623 7624
*/