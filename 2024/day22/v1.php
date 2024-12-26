<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const MOD = 16777216;
const MULT = 64;
const DIVIDE = 32;
const MULT2 = 2048;

const STEPS = 2000;

$buyer = [];
foreach($lines as $l) {
	$num = intval($l);
	$buyer[$num] = calc($num, STEPS);
}

echo '<pre>';
var_dump(array_sum($buyer));
echo '</pre>';
die();

function calc(int $secret, int $steps): int
{
	// var_dump($secret);

	for($i=0; $i<$steps; $i++) {
		$secret = mixAndPrune($secret, $secret * MULT);
		$secret = mixAndPrune($secret, floor($secret / DIVIDE));
		$secret = mixAndPrune($secret, $secret * MULT2);

		// echo '<pre>';
		// var_dump($secret);
		// echo '</pre>';
	}

	return $secret;
}

function mixAndPrune($secret, $num) {
	return ($num ^ $secret) % MOD;
}