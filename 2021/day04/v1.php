<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$turns = [];
$boards = [];
$boardIndex = -1;

// parse turns and boards
foreach($lines as $num => $l) {
	if ($num == 0) {
		$turns = explode(',', $l);
	} else if ($l != '') {

		if (!isset($boards[count($boards)])) {
			$boards[count($boards)] = [];
		}

		$row = array_filter(explode(' ', $lines[$num]), function($val) {
			return $val != '';
		});

		$tempRow = [];
		foreach($row as $r => $val) {
			$tempRow[$val] = false;
		}

		$boards[$boardIndex][] = $tempRow;
	} else {
		$boardIndex++;
	}
}

$boards = array_filter($boards);



foreach($turns as $t) {
	markBoards($t, $boards);

	$winningBoardNum = false;
	foreach($boards as $boardIndex =>$board) {
		if (isBingo($board)) {
			$winningBoardNum = $boardIndex;
			break;
		}
	}

	if ($winningBoardNum !== false) {
		$score = scoreBoard($boards[$winningBoardNum], $t);
		break;
	}
}

echo '<pre>';
var_dump($score);
echo '</pre>';
die();




function isBingo(array $board) {
	// check rows
	foreach($board as $row) {
		$temp = array_filter($row);

		if (count($temp) == 5) {
			return true;
		}
	}

	// check columns
	for($i=0; $i<4; $i++) {
		$col = [];
		foreach($board as $row) {
			$keys = array_keys($row);
			$col[] = $row[$keys[$i]];
		}

		$temp = array_filter($col);

		if (count($temp) == 5) {
			return true;
		}
	}
}

function markBoards(int $turn, array &$boards) {
	foreach($boards as $boardIndex => $board) {
		foreach($board as $rowIndex => $row) {
			foreach($row as $val => $bool) {
				if ($val == $turn) {
					$boards[$boardIndex][$rowIndex][$val] = true;
				}
			}
		}
	}
}

function scoreBoard(array $board, int $turn) {
	$score = 0;

	foreach($board as $rowIndex => $row) {
		foreach($row as $val => $bool) {
			if (!$bool) {
				$score += $val;
			}
		}
	}

	return $score * $turn;
}