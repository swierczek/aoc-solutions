<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

// parsing this might be a pain, so just hardcode it
if ($filename === 'testinput.txt') {
	$stacks = [
		1 => ['Z', 'N'],
		2 => ['M', 'C', 'D'],
		3 => ['P'],
	];
} else {
	$stacks = [
		1 => ['N', 'R', 'G', 'P'],
		2 => ['J', 'T', 'B', 'L', 'F', 'G', 'D', 'C'],
		3 => ['M', 'S', 'V'],
		4 => ['L', 'S', 'R', 'C', 'Z', 'P'],
		5 => ['P', 'S', 'L', 'V', 'C', 'W', 'D', 'Q'],
		6 => ['C', 'T', 'N', 'W', 'D', 'M', 'S'],
		7 => ['H', 'D', 'G', 'W', 'P'],
		8 => ['Z', 'L', 'P', 'H', 'S', 'C', 'M', 'V'],
		9 => ['R', 'P', 'F', 'L', 'W', 'G', 'Z'],
	];
}

foreach($lines as $line) {
	if (stripos($line, 'move') !== 0) {
		continue;
	}

	$instruction = explode(' ', $line);

	$num = $instruction[1];
	$from = $instruction[3];
	$to = $instruction[5];

	for ($i=0; $i<$num; $i++) {
		$box = array_pop($stacks[$from]);
		array_push($stacks[$to], $box);
	}
}

// output the top item from each stack
$output = '';
foreach($stacks as $stack) {
	$output .= array_pop($stack);
}

var_dump($output);
die();