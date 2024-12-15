<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('WIDTH', stripos($filename, '/input.txt') !== false ? 101 : 11);
define('HEIGHT', stripos($filename, '/input.txt') !== false ? 103 : 7);
define('STEPS', 100);

$bots = [];
foreach($lines as $l) {
	preg_match('/^p=(\d+),(\d+) v=(-?\d+),(-?\d+)$/', $l, $matches);

	$bots[] = [
		'py' => intval($matches[2]),
		'px' => intval($matches[1]),
		'vy' => intval($matches[4]),
		'vx' => intval($matches[3]),
	];
}

$q = [
	0,
	0,
	0,
	0,
];
foreach($bots as $key => $b) {
	$newY = $b['py'] + (($b['vy'] * STEPS) % HEIGHT);
	$newX = $b['px'] + (($b['vx'] * STEPS) % WIDTH);

	if ($newY < 0) {
		$newY += HEIGHT;
	} else if ($newY > HEIGHT-1) {
		$newY = $newY % HEIGHT;
	}

	if ($newX < 0) {
		$newX += WIDTH;
	} else if ($newX > WIDTH-1) {
		$newX = $newX % WIDTH;
	}

	// $bots[$key]['py'] = $newY;
	// $bots[$key]['px'] = $newX;

	$midY = intval(floor(HEIGHT / 2));
	$midX = intval(floor(WIDTH / 2));

	// if ($newY === $midY || $newX === $midX) {
		// echo "bot in middle, ignoring\n";
	if ($newY < $midY && $newX < $midX) {
		$q[0]++;
	} else if ($newY < $midY && $newX > $midX) {
		$q[1]++;
	} else if ($newY > $midY && $newX < $midX) {
		$q[2]++;
	} else if ($newY > $midY && $newX > $midX) {
		$q[3]++;
	}
}

echo '<pre>';
var_dump(array_product($q));
echo '</pre>';
die();
