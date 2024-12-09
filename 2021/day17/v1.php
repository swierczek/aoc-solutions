<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$xi = 20;
$xo = 30;
$yi = -10;
$yo = -5;

/*
	on each step:
		$x increases by x velocity
		$y increases by y velocity

		x velocity decreases by 1 (toward 0)
		y velocity decreases by 1
*/

$xv = 0;
$yv = 0;
$xp = 0;
$yp = 0;

// for each step
$xp += $xv;
$yp += $yv;

$xp--;
$yp--;
