<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('CUBE', '#');
define('ROCK', 'O');
define('NOTHING', '.');

$panel = [];
foreach($lines as $l) {
	$panel[] = str_split($l);
}

$scores = [];
for($x=0; $x<count($panel[0]); $x++) {
	$col = array_column($panel, $x);

	$parts = explode(CUBE, implode($col));

	foreach($parts as $key => $val) {
		$numRocks = substr_count($val, ROCK);
		$parts[$key] = str_pad(str_repeat(ROCK, $numRocks), strlen($val), NOTHING);
	}

	$colShift = implode(CUBE, $parts);

	$score = scoreRocks($colShift);

	$scores[] = $score;

	echo '<pre>';
	var_dump($colShift);
	echo '</pre>';
	// die();
}

echo '<pre>';
var_dump($scores);
var_dump(array_sum($scores));
echo '</pre>';
die();

// echo '<pre>';
// var_dump($panel);
// echo '</pre>';
// die();

function scoreRocks($input)
{
	$arr = str_split($input);

	$score = 0;
	for($i=0; $i<count($arr); $i++) {
		if ($arr[$i] === ROCK) {
			$score += count($arr) - $i;
		}
	}

	return $score;
}