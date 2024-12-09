<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('VERT', '|');
define('HORIZ', '-');
define('NE', 'L');
define('NW', 'J');
define('SW', '7');
define('SE', 'F');
define('START', 'S');

define('POINT_R', [HORIZ, NE, SE]);
define('POINT_L', [HORIZ, NW, SW]);
define('POINT_U', [VERT, NE, NW]);
define('POINT_D', [VERT, SE, SW]);

$map = [];
$currX = -1;
$currY = -1;
$startX = -1;
$startY = -1;
foreach($lines as $y => $l) {
	$l = str_split($l);

	foreach($l as $x => $val) {
		if ($val === START) {
			$startX = $x;
			$startY = $y;
		}

		$l[$x] = [
			'char' => $l[$x],
			'x' => $x,
			'y' => $y,
			'visited' => false,
		];
	}

	$map[] = $l;
}

// determine start space
$map[$startY][$startX]['char'] = determineStartChar($startX, $startY);

// start
$next = $map[$startY][$startX];
$pipe = '';
$count = 0;

do {
	$next = findNext($next);
	$map[$next['y']][$next['x']]['visited'] = true;
	$pipe .= $next['char'];
	$count++;
} while (($next['x'] !== $startX || $next['y'] !== $startY));

echo '<pre>';
var_dump(strlen($pipe) / 2);
// var_dump($pipe);
echo '</pre>';
die();

function findNext($curr) {
	global $map;
	$x = $curr['x'];
	$y = $curr['y'];

	if (connectsUp($curr)) {
		return $map[$y-1][$x];
	} else if (connectsRight($curr)) {
		return $map[$y][$x+1];
	} else if (connectsDown($curr)) {
		return $map[$y+1][$x];
	} else if (connectsLeft($curr)) {
		return $map[$y][$x-1];
	} else {
		echo '<pre>';
		var_dump('wtf');
		echo '</pre>';
		die();
	}
}

function connectsUp($curr)
{
	global $map;
	$next = @$map[$curr['y']-1][$curr['x']];
	$c = @$curr['char'];
	$n = @$next['char'];
	$v = @$next['visited'];

	return !$v && in_array($c, [VERT, NE, NW]) && in_array($n, [VERT, SW, SE]);
}

function connectsDown($curr)
{
	global $map;
	$next = @$map[$curr['y']+1][$curr['x']];
	$c = @$curr['char'];
	$n = @$next['char'];
	$v = @$next['visited'];

	return !$v && in_array($c, [VERT, SE, SW]) && in_array($n, [VERT, NW, NE]);
}

function connectsRight($curr)
{
	global $map;
	$next = @$map[$curr['y']][$curr['x']+1];
	$c = @$curr['char'];
	$n = @$next['char'];
	$v = @$next['visited'];

	return !$v && in_array($c, [HORIZ, NE, SE]) && in_array($n, [HORIZ, NW, SW]);
}

function connectsLeft($curr)
{
	global $map;
	$next = @$map[$curr['y']][$curr['x']-1];
	$c = @$curr['char'];
	$n = @$next['char'];
	$v = @$next['visited'];

	return !$v && in_array($c, [HORIZ, NW, SW]) && in_array($n, [HORIZ, NE, SE]);
}

function determineStartChar($x, $y)
{
	global $map;

	if (
		in_array(@$map[$y+1][$x]['char'], POINT_U)
		&& in_array(@$map[$y-1][$x]['char'], POINT_D)
	) {
		return VERT;
	} else if (
		in_array(@$map[$y][$x-1]['char'], POINT_R)
		&& in_array(@$map[$y][$x+1]['char'], POINT_L)
	) {
		return HORIZ;
	} else if (
		in_array(@$map[$y-1][$x]['char'], POINT_D)
		&& in_array(@$map[$y][$x+1]['char'], POINT_L)
	) {
		return NE;
	} else if (
		in_array(@$map[$y+1][$x]['char'], POINT_U)
		&& in_array(@$map[$y][$x+1]['char'], POINT_L)
	) {
		return SE;
	} else if (
		in_array(@$map[$y-1][$x]['char'], POINT_D)
		&& in_array(@$map[$y][$x-1]['char'], POINT_R)
	) {
		return NW;
	} else if (
		in_array(@$map[$y+1][$x]['char'], POINT_U)
		&& in_array(@$map[$y][$x-1]['char'], POINT_R)
	) {
		return SW;
	}
}