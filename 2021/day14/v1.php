<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$input = [];
$rules = [];

foreach($lines as $l) {
	if (!$l) {
		continue;
	}

	if (!$input) {
		$input = str_split($l);
	} else {
		list($pair, $insert) = explode(' -> ', $l);
		$rules[$pair] = $insert;
	}
}

$steps = 10;
$new = $input;
for($i=1; $i<=$steps; $i++) {
	$pairs = [];
	for($j=0; $j<count($new)-1; $j++) {
		$pairs[] = $new[$j] . $new[$j+1];
	}

	$count = 0;
	foreach($pairs as $p) {
		$count++;
		if (isset($rules[$p])) {
			$left = array_slice($new, 0, $count);
			$right = array_slice($new, $count);

			$new = array_merge($left, [$rules[$p]], $right);
			$count++;
		}
	}
}

$vals = array_count_values($new);
$max = max($vals);
$min = min($vals);

$diff = $max - $min;

echo '<pre>';
var_dump($diff);
echo '</pre>';
die();
