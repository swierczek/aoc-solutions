<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const SEP = ',';

$cubes = [];
foreach($lines as $line) {
	$coords = explode(SEP, $line);
	$x = $coords[0];
	$y = $coords[1];
	$z = $coords[2];

	$cubes[$x][$y][$z] = true;
}

const MAX = 22;

$total = 0;

for($x=-MAX; $x<=MAX; $x++) {
	for($y=-MAX; $y<=MAX; $y++) {

		// check from back to front
		for($z=-MAX; $z<=MAX; $z++) {
			if (isset($cubes[$x][$y][$z])) {
				echo "block found back at $x, $y, $z\n";
				$total++;
				break;
			}
		}

		// check from front to back
		for($z=MAX; $z>=-MAX; $z--) {
			if (isset($cubes[$x][$y][$z])) {
				echo "block found front at $x, $y, $z\n";
				$total++;
				break;
			}
		}
	}
}

for($y=-MAX; $y<=MAX; $y++) {
	for($z=-MAX; $z<=MAX; $z++) {

		// check from left to right
		for($x=-MAX; $x<=MAX; $x++) {
			if (isset($cubes[$x][$y][$z])) {
				echo "block found left at $x, $y, $z\n";
				$total++;
				break;
			}
		}

		// check from right to left
		for($x=MAX; $x>=-MAX; $x--) {
			if (isset($cubes[$x][$y][$z])) {
				echo "block found right at $x, $y, $z\n";
				$total++;
				break;
			}
		}
	}
}

for($z=-MAX; $z<=MAX; $z++) {
	for($x=-MAX; $x<=MAX; $x++) {

		// check from bottom to top
		for($y=-MAX; $y<=MAX; $y++) {
			if (isset($cubes[$x][$y][$z])) {
				echo "block found bottom at $x, $y, $z\n";
				$total++;
				break;
			}
		}

		// check from top to bottom
		for($y=MAX; $y>=-MAX; $y--) {
			if (isset($cubes[$x][$y][$z])) {
				echo "block found top at $x, $y, $z\n";
				$total++;
				break;
			}
		}
	}
}

echo '<pre>';
var_dump($total);
echo '</pre>';
die();
