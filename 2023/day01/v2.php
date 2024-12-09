<?php

$filename = $argv[1] ?? 'testinput2.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$map = [
	'one' => 1,
	'two' => 2,
	'three' => 3,
	'four' => 4,
	'five' => 5,
	'six' => 6,
	'seven' => 7,
	'eight' => 8,
	'nine' => 9,
];

// [ 'eno' => 1, 'owt' => 2, ...]
$reverseMap = [];
foreach($map as $key => $val) {
	$reverseMap[strrev($key)] = $val;
}

$sum = 0;
foreach($lines as $l) {
	$match = matchDigit($l, $map);
	$reverseMatch = matchDigit(strrev($l), $reverseMap);

	$lineSum = (10 * $match) + $reverseMatch;
	$sum += $lineSum;
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();

function matchDigit($string, $map): int
{
	preg_match(
		'/('
		. implode('|', array_keys($map))
		. '|'
		. implode('|', array_values($map))
		. ')/'
	, $string, $matches);

	$match = $matches[0];

	return (isset($map[$match])) ? $map[$match] : $match;
}