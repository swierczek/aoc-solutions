<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const NOOP = 'noop';
const ADDX = 'addx ';

$cycle = 0;
$x = 1;
$signal = 0;
$sum = 0;

$pendingAdd = 0;
$i=0;

while (true) {
	$cycle++;

	echo "start cycle $cycle\n";

	if ($cycle === 20 || ($cycle-20) % 40 === 0) {
		$signal = $cycle * $x;
		echo "cycle $cycle * x $x = signal $signal\n";
		$sum += $signal;
	}

	if ($pendingAdd !== 0) {
		$x += $pendingAdd;
		$pendingAdd = 0;
		echo "setting x to $x\n";
	} else {
		if ($i >= count($lines)) {
			echo "ending execution\n";
			break;
		}

		$line = $lines[$i];

		if ($line === NOOP) {
			// nothing
			echo "noop\n";
		} else {
			$pendingAdd = intval(str_replace(ADDX, '', $line));
			echo "setting pending add to $pendingAdd\n";
		}
	}

	if ($pendingAdd === 0) {
		$i++;
	}

	echo "end cycle $cycle\n";
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();