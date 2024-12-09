<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

// Rock:     A
// Paper:    B
// Scissors: C

// Lose: X
// Draw: Y
// Win:  Z

const ROCK = 1;
const PAPER = 2;
const SCISSORS = 3;

const LOSE = 'X';
const DRAW = 'Y';
const WIN = 'Z';

$rps = [
	ROCK => 'A',
	PAPER => 'B',
	SCISSORS => 'C',
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
		if ($myMove == LOSE) {
			$score += 0; // lose
			$score += SCISSORS;
		} else if ($myMove == DRAW) {
			$score += 3; // tie
			$score += ROCK;
		} else if ($myMove == WIN) {
			$score += 6; // win
			$score += PAPER;
		}
	} else if ($opponentMove == $rps[PAPER]) {
		if ($myMove == LOSE) {
			$score += 0; // lose
			$score += ROCK;
		} else if ($myMove == DRAW) {
			$score += 3; // tie
			$score += PAPER;
		} else if ($myMove == WIN) {
			$score += 6; // win
			$score += SCISSORS;
		}
	} else if ($opponentMove == $rps[SCISSORS]) {
		if ($myMove == LOSE) {
			$score += 0; // lose
			$score += PAPER;
		} else if ($myMove == DRAW) {
			$score += 3; // tie
			$score += SCISSORS;
		} else if ($myMove == WIN) {
			$score += 6; // win
			$score += ROCK;
		}
	}
}

echo '<pre>';
var_dump($score);
echo '</pre>';
die();