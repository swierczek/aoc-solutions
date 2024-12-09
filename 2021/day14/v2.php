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

// process the initial pairs as a starting point
$pairs = [];
for($j=0; $j<count($input)-1; $j++) {
	$pair = $input[$j] . $input[$j+1];
	if (!isset($pairs[$pair])) {
		$pairs[$pair] = 0;
	}
	$pairs[$pair]++;
}

$steps = 40;
for($i=1; $i<=$steps; $i++) {

	$newPairs = [];
	foreach($pairs as $pair => $count) {
		if (isset($rules[$pair])) {
			$first = $pair[0] . $rules[$pair];
			$second = $rules[$pair] . $pair[1];

			@$newPairs[$first] += $count;
			@$newPairs[$second] += $count;
		}
	}

	$pairs = $newPairs;
}


$counts = [];
foreach($pairs as $pair => $count) {
	$first = $pair[0];
	$second = $pair[1];

	@$counts[$first] += $count;
	@$counts[$second] += $count;
}

// then add 1 extra for the first/last letters
$counts[$input[0]]++;
$counts[$input[count($input)-1]]++;

// sort to find first/last items
asort($counts);

// workaround to find the first associative element
$rev = array_reverse($counts);
$pop = array_pop($rev);

$diff = (end($counts) - $pop) / 2;

echo '<pre>';
var_dump($diff);
echo '</pre>';
die();
