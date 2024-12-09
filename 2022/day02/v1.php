<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

// Rock:     A,X
// Paper:    B,Y
// Scissors: C,Z

const ROCK = 1;
const PAPER = 2;
const SCISSORS = 3;

$rps = [
	ROCK => 'A',
	PAPER => 'B',
	SCISSORS => 'C',
];

$xyz = [
	ROCK => 'X',
	PAPER => 'Y',
	SCISSORS => 'Z',
];

$score = 0;

foreach($lines as $line) {
	if (!$line) {
		continue;
	}

	$l = explode(' ', $line);
	$opponentMove = $l[0];
	$myMove = $l[1];

	if ($opponentMove == $rps[ROCK]) {
		if ($myMove == $xyz[ROCK]) {
			$score += 3; // tie
		} else if ($myMove == $xyz[PAPER]) {
			$score += 6; // win
		} else if ($myMove == $xyz[SCISSORS]) {
			$score += 0; // lose
		}
	} else if ($opponentMove == $rps[PAPER]) {
		if ($myMove == $xyz[ROCK]) {
			$score += 0; // lose
		} else if ($myMove == $xyz[PAPER]) {
			$score += 3; // tie
		} else if ($myMove == $xyz[SCISSORS]) {
			$score += 6; // win
		}
	} else if ($opponentMove == $rps[SCISSORS]) {
		if ($myMove == $xyz[ROCK]) {
			$score += 6; // win
		} else if ($myMove == $xyz[PAPER]) {
			$score += 0; // lose
		} else if ($myMove == $xyz[SCISSORS]) {
			$score += 3; // tie
		}
	}

	$score += array_search($myMove, $xyz);
}

echo '<pre>';
var_dump($score);
echo '</pre>';
die();