<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('DEST', 'ZZZ');

$steps = [];
$map = [];
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
			'visited' => false,
		];
	}
}

$count = 0;
$i = 0;
$current = 'AAA';
while ($current !== DEST) {
	echo $current . "\n";
	// echo $steps[$i] . "\n";
	// echo $i . "\n";

	$lr = $steps[$i];

	$current = $map[$current][$lr];

	if ($i >= count($steps)-1) {
		$i = 0;
	} else {
		$i++;
	}

	$count++;
}

echo DEST . "\n";

echo '<pre>';
var_dump($count);
echo '</pre>';
die();