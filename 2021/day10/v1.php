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
$closings = array_values($pairs);

$points = [
	')' => 3,
	']' => 57,
	'}' => 1197,
	'>' => 25137,
];

$invalid = [];
$incomplete = [];
$illegals = [];
foreach($lines as $l) {
	$chars = str_split($l);

	$expected = [];
	$lineComplete = true;
	foreach($chars as $c) {
		if (in_array($c, $openings)) {
			// echo "$c is opener".PHP_EOL;
			array_push($expected, $pairs[$c]);
		} else if (count($expected) > 0 && $c == $expected[count($expected)-1]) {
			// echo "$c is matched expected".PHP_EOL;
			array_pop($expected);
		} else {
			// echo "$c is illegal".PHP_EOL;
			$illegals[] = $c;
			$invalid[] = $l;
			$lineComplete = false;
			break;
		}
	}

	if ($lineComplete) {
		$incomplete[] = $l;
	}
}

$score = 0;
foreach($illegals as $i) {
	$score += $points[$i];
}

echo $score.PHP_EOL;
die();
