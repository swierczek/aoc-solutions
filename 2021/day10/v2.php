<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));



$pairs = [
	'(' => ')',
	'[' => ']',
	'{' => '}',
	'<' => '>',
];

$openings = array_keys($pairs);;

$points = [
	')' => 1,
	']' => 2,
	'}' => 3,
	'>' => 4,
];

$autocompletes = [];

foreach($lines as $l) {
	$chars = str_split($l);

	$expected = [];
	$lineComplete = true;
	foreach($chars as $c) {
		if (in_array($c, $openings)) {
			array_push($expected, $pairs[$c]);
		} else if (count($expected) > 0 && $c == $expected[count($expected)-1]) {
			array_pop($expected);
		} else {
			// invalid character found, stop checking this line
			$lineComplete = false;
			break;
		}
	}

	if ($lineComplete) {
		$autocompletes[] = array_reverse($expected);
	}
}

$scores = [];
foreach($autocompletes as $a) {
	$score = 0;

	foreach($a as $char) {
		$score *= 5;
		$score += $points[$char];
	}

	$scores[] = $score;
}

sort($scores);

$middle = $scores[floor(count($scores) / 2)];

echo $middle.PHP_EOL;
die();
