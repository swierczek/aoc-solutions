<?php

$filename = $argv[1] ?? 'test3.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('START_CHAR', 'A');
define('END_CHAR', 'Z');

$steps = [];
$map = [];
$active = [];
foreach($lines as $lineNum => $l) {
	if ($lineNum === 0) {
		$steps = str_split($l);
	} else if ($l !== '') {
		$split = explode(' = ', $l);
		$key = $split[0];
		$vals = str_replace(['(', ')'], '', $split[1]);

		$lrSplit = explode(', ', $vals);
		$left = $lrSplit[0];
		$right = $lrSplit[1];

		$map[$key] = [
			'L' => $left,
			'R' => $right,
		];

		if ($key[strlen($key)-1] === START_CHAR) {
			$active[] = $key;
		}
	}
}

$count = 0;
$i = 0;
$allEnd = false;
$ends = [];

while ($allEnd === false) {
	// get the next step for all nodes
	$lr = $steps[$i];

	// iterate each unended node
	foreach($active as $key => $val) {
		if (isset($ends[$key])) {
			continue;
		}

		$active[$key] = $map[$val][$lr];
	}

	// increment i for the next loop
	if ($i >= count($steps)-1) {
		$i = 0;
	} else {
		$i++;
	}

	// count this step
	$count++;

	// check if all are on end nodes
	$allEnd = true;
	foreach($active as $j => $key) {
		if ($key[strlen($key)-1] === END_CHAR) {
			if (!isset($ends[$j])) {
				$ends[$j] = $count;
			}
		} else {
			$allEnd = false;
		}
	}
}

// dumb
$lcm = gmp_lcm(
		$ends[0],
		gmp_lcm(
			$ends[1],
			gmp_lcm(
				$ends[2],
				gmp_lcm(
					$ends[3],
					gmp_lcm(
						$ends[4],
						$ends[5]
					)
				)
			)
		)
	);

echo '<pre>';
var_dump($lcm);
echo '</pre>';
die();
