<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$scores = [];
foreach($lines as $cardNum => $l) {
	$l = preg_replace('/Card\s\d+:\s+/', '', $l);
	$split = explode(' | ', $l);
	$wins = array_filter(preg_split('/\s/', $split[0]));
	$yours = array_filter(preg_split('/\s/', $split[1]));

	$winningNums = array_flip($wins);

	$score = 0;
	foreach($yours as $val) {
		if (isset($winningNums[$val])) {
			if ($score === 0) {
				$score = 1;
			} else {
				$score *= 2;
			}
		}
	}

	$scores[$cardNum] = $score;
}

echo '<pre>';
var_dump(array_sum($scores));
echo '</pre>';
die();
