<?php

$filename = $argv[1] ?? 'input.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$x = 0;
$y = 0;

foreach($lines as $line) {
	$parsed = explode(' ', $line);
	$direction = $parsed[0];
	$amount = intval($parsed[1]);

	switch ($direction) {
		case 'forward':
			$x += $amount;
			break;

		case 'down':
			$y += $amount;
			break;

		case 'up':
			$y -= $amount;
			break;
	}
}

echo $x * $y;