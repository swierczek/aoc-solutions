<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$paper = [];
$points = [];
$folds = [];

$largestX = 0;
$largestY = 0;
$mode = 'grid';
foreach($lines as $l) {
	if ($l == '') {
		$mode = 'folds';
		continue;
	}

	if ($mode == 'grid') {
		list($x, $y) = explode(',', $l);

		if ($x > $largestX) {
			$largestX = $x;
		}

		if ($y > $largestY) {
			$largestY = $y;
		}

		$points[$x][$y] = '#';
	} else {
		list($left, $amount) = explode('=', $l);
		$direction = substr($left, -1);
		$folds[] = ['direction' => $direction, 'amount' => $amount];
	}
}

// build the starting grid
$paper = array_fill(0, $largestX+1, 0);
foreach($paper as $key => $g) {
	$paper[$key] = array_fill(0, $largestY+1, '.');
}

foreach($points as $x => $row) {
	foreach($row as $y => $col) {
		$paper[$x][$y] = '#';
	}
}





$countVisible = 0;
$foldNum = 0;
foreach($folds as $fold) {
	$foldNum++;

	$newPaper = [];

	$dir = $fold['direction'];
	$amount = intval($fold['amount']);

	$xLength = count($paper);
	$yLength = count($paper[0]);

	if ($dir == 'x') {
		//first part is normal
		for($y=0; $y<$yLength; $y++) {
			for($x=0; $x<$amount; $x++) {
				$newPaper[$x][$y] = $paper[$x][$y];
			}
		}

		// from index 0 - 15
		for($y=0; $y<$yLength; $y++) {
			// from index 5-10?
			for($x=1; $x<$xLength - $amount; $x++) {
				$oldChar = $paper[$amount + $x][$y];
				$newChar = $newPaper[$amount - $x][$y];

				// we only need to overwrite the new value if it's not already a #
				if ($newChar == '.') {
					$newPaper[$amount - $x][$y] = $paper[$amount + $x][$y];
				}
			}
		}
	} else if ($dir == 'y') {
		//first part is normal
		for($y=0; $y<$amount; $y++) {
			for($x=0; $x<$xLength; $x++) {
				$newPaper[$x][$y] = $paper[$x][$y];
			}
		}

		// from index 1-8?
		for($y=1; $y<$yLength - $amount; $y++) {
			// from index 0 - 11
			for($x=0; $x<$xLength; $x++) {
				$oldChar = $paper[$x][$amount + $y];
				$newChar = $newPaper[$x][$amount - $y];

				// we only need to overwrite the new value if it's not already a #
				if ($newChar == '.') {
					$newPaper[$x][$amount - $y] = $paper[$x][$amount + $y];
				}
			}
		}
	}

	$paper = $newPaper;
}

printGrid($paper);
die();


function printGrid($paper) {
	for($y=0; $y<count($paper[0]); $y++) {
		for($x=0; $x<count($paper); $x++) {
			echo $paper[$x][$y];
		}

		echo "\n";
	}

	echo "\n\n";
}
