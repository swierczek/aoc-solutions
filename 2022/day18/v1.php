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

	$cubes[$x][$y][$z] = getExposedSides($cubes, $x, $y, $z);
}

$total = 0;
foreach($cubes as $cX) {
	foreach($cX as $cY) {
		foreach($cY as $val) {
			$total += $val;
		}
	}
}

echo '<pre>';
var_dump($total);
echo '</pre>';
die();

function getExposedSides(&$cubes, $x, $y, $z) {
	$front = $cubes[$x][$y][$z-1] ?? null;
	$back = $cubes[$x][$y][$z+1] ?? null;
	$left = $cubes[$x-1][$y][$z] ?? null;
	$right = $cubes[$x+1][$y][$z] ?? null;
	$top = $cubes[$x][$y+1][$z] ?? null;
	$bottom = $cubes[$x][$y-1][$z] ?? null;

	$count = 6;
	if ($front !== null) {
		$cubes[$x][$y][$z-1]--;
		$count--;
	}
	if ($back !== null) {
		$cubes[$x][$y][$z+1]--;
		$count--;
	}
	if ($left !== null) {
		$cubes[$x-1][$y][$z]--;
		$count--;
	}
	if ($right !== null) {
		$cubes[$x+1][$y][$z]--;
		$count--;
	}
	if ($top !== null) {
		$cubes[$x][$y+1][$z]--;
		$count--;
	}
	if ($bottom !== null) {
		$cubes[$x][$y-1][$z]--;
		$count--;
	}

	return $count;
}
