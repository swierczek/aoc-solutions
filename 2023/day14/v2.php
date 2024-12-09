<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('CUBE', '#');
define('ROCK', 'O');
define('NOTHING', '.');

// define('CYCLES', 3);
// define('CYCLES', 10000); // 2 seconds with test input
define('CYCLES', 100000); // 8 seconds with test input
// define('CYCLES', 1000000000);

$panel = [];
foreach($lines as $l) {
	$panel[] = str_split($l);
}



for($i=0; $i<CYCLES; $i++) {
	// N
	$panel = tiltAndRotate($panel);
	printGrid($panel);
	$panel = tiltAndRotate($panel);
	printGrid($panel);
	$panel = tiltAndRotate($panel);
	printGrid($panel);
	$panel = tiltAndRotate($panel);
	printGrid($panel);

	die;

	// W
	// $panel = tilt($panel);
	// $panel = rotate($panel);

	// S
	// $panel = tilt($panel);
	// $panel = rotate($panel);

	// E
	// $panel = tilt($panel);
	// $panel = rotate($panel);
}

// printGrid($panel);
die();

$scores = [];

function tiltAndRotate($panel)
{
	$new = [];

	for($x=0; $x<count($panel[0]); $x++) {
		$col = array_column($panel, $x);

		$parts = explode(CUBE, implode($col));

		foreach($parts as $key => $val) {
			$numRocks = substr_count($val, ROCK);
			$parts[$key] = str_pad(str_repeat(ROCK, $numRocks), strlen($val), NOTHING);
		}

		$colShift = strrev(implode(CUBE, $parts));

		$new[] = str_split($colShift);

		// $newCol = str_split($colShift);

		// echo '<pre>';
		// var_dump($newCol);
		// echo '</pre>';
		// die();

		// write the columns back to new array
		// foreach($newCol as $key => $n) {
		// 	$new[$key][$x] = $n;
		// }
	}

	return $new;
}

function tilt($panel)
{
	$new = [];

	for($x=0; $x<count($panel[0]); $x++) {
		$col = array_column($panel, $x);

		$parts = explode(CUBE, implode($col));

		foreach($parts as $key => $val) {
			$numRocks = substr_count($val, ROCK);
			$parts[$key] = str_pad(str_repeat(ROCK, $numRocks), strlen($val), NOTHING);
		}

		$colShift = implode(CUBE, $parts);

		$newCol = str_split($colShift);

		// write the columns back to new array
		foreach($newCol as $key => $n) {
			$new[$key][$x] = $n;
		}
	}

	return $new;
}

function rotate($panel)
{
	$new = [];

	for($x=0; $x<count($panel[0]); $x++) {
		$col = array_column($panel, $x);

		$new[] = array_reverse($col);

		// $parts = explode(CUBE, implode($col));

		// foreach($parts as $key => $val) {
		// 	$numRocks = substr_count($val, ROCK);
		// 	$parts[$key] = str_pad(str_repeat(ROCK, $numRocks), strlen($val), NOTHING);
		// }

		// $colShift = implode(CUBE, $parts);

		// $newCol = str_split($colShift);

		// write the columns back to new array
		// foreach($newCol as $key => $n) {
		// 	$new[$key][$x] = $n;
		// }
	}

	return $new;
}

printGrid($new);

echo '<pre>';
var_dump($scores);
var_dump(array_sum($scores));
echo '</pre>';
die();

// echo '<pre>';
// var_dump($panel);
// echo '</pre>';
// die();

// TODO: input array instead of a column
function scoreRocks($input)
{
	$arr = str_split($input);

	$score = 0;
	for($i=0; $i<count($arr); $i++) {
		if ($arr[$i] === ROCK) {
			$score += count($arr) - $i;
		}
	}

	return $score;
}

// function flipDiagonally($arr) {
//     $out = array();
//     foreach ($arr as $key => $subarr) {
//         foreach ($subarr as $subkey => $subvalue) {
//             $out[$subkey][$key] = $subvalue;
//         }
//     }
//     return $out;
// }

function printGrid($grid)
{
	foreach($grid as $y => $thing) {
		foreach ($thing as $x => $val) {
			echo $val;
		}

		echo "\n";
	}

	echo "\n";
}
