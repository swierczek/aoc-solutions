<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$cards = [];
foreach($lines as $cardNum => $l) {
	$cardNum++; // 1 indexed

	if (!isset($cards[$cardNum])) {
		$cards[$cardNum] = 0;
	}

	$cards[$cardNum] += 1;

	$l = preg_replace('/Card\s\d+:\s+/', '', $l);
	$split = explode(' | ', $l);
	$wins = array_filter(preg_split('/\s/', $split[0]));
	$yours = array_filter(preg_split('/\s/', $split[1]));

	$winningNums = array_flip($wins);

	$winCount = 0;
	foreach($yours as $val) {
		$winCount += isset($winningNums[$val]) ? 1 : 0;
	}

	for($i=1; $i<=$winCount; $i++) {
		$key = $cardNum + $i;
		if (!isset($cards[$key])) {
			$cards[$key] = 0;
		}

		$cards[$key] += $cards[$cardNum];
	}
}

echo '<pre>';
var_dump(array_sum($cards));
echo '</pre>';
die();

