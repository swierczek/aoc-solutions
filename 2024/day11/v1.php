<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

foreach($lines as $l) {
	$stones = array_map('intval', explode(' ', $l));
}

$steps = 25;
for($i=0; $i<$steps; $i++) {
	$stones = step($stones);

	// var_dump(count($stones));
	// var_dump(implode(' ', $stones));
}

echo '<pre>';
var_dump(count($stones));
echo '</pre>';
die();

function step($stones): array
{
	$next = [];

	foreach($stones as $stone) {
		$step = nextStone($stone);

		foreach($step as $s) {
			$next[] = $s;
		}
	}

	return $next;
}

function nextStone($stone): array
{
	if ($stone === 0) {
		// echo "returning 1\n";
		return [1];
	}

	if (strlen($stone) % 2 === 0) {
		$len = strlen($stone);

		// echo "returning split\n";
		return [
			intval(substr($stone, 0, $len / 2)),
			intval(substr($stone, $len / 2)),
		];
	}

	// echo "returning mult\n";
	return [$stone * 2024];
}