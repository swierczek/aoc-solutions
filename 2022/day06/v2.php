<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const LIMIT = 14;

foreach($lines as $line) {
	$queue = [];

	$chars = str_split($line);

	foreach($chars as $index => $c) {
		if (count($queue) < LIMIT) {
			array_push($queue, $c);
			continue;
		}

		array_push($queue, $c);
		unset($queue[array_keys($queue)[0]]);

		if (count(array_unique($queue)) == LIMIT) {
			var_dump($index+1);
			break;
		}
	}
}
