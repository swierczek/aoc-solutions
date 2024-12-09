<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$count = 0;

foreach($lines as $l) {
	$parts = explode(' | ', $l);
	$inputs = explode(' ', $parts[1]);

	foreach($inputs as $i) {
		$len = strlen($i);
		if (in_array($len, [2, 3, 4, 7])) {
			$count++;
		}
	}

}

echo '<pre>';
var_dump($count);
echo '</pre>';
die();