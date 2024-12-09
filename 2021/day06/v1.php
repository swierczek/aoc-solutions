<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$fish = explode(',', $lines[0]);

// # days
for ($i=0; $i<80; $i++) {
	// echo implode(',', $fish).PHP_EOL;

	foreach($fish as $key => $f) {
		$fish[$key]--;

		if ($fish[$key] < 0) {
			$fish[$key] = 6;
			$fish[] = 8;
		}
	}
}

echo '<pre>';
var_dump(count($fish));
echo '</pre>';
die();