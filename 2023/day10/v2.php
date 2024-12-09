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
define('NOTHING', '.');

define('POINT_R', [HORIZ, NE, SE]);
define('POINT_L', [HORIZ, NW, SW]);
define('POINT_U', [VERT, NE, NW]);
define('POINT_D', [VERT, SE, SW]);

define('R', 'right');
define('L', 'left');
define('U', 'up');
define('D', 'down');


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
			'newchar' => $l[$x],
			'x' => $x,
			'y' => $y,
			'visited' => false,
			'enclosed' => false,
			'left' => 0,
			'right' => 0,
		];
	}

	$map[] = $l;
}

// determine start space
$map[$startY][$startX]['char'] = determineStartChar($startX, $startY);
$map[$startY][$startX]['newchar'] = $map[$startY][$startX]['char'];

// start
$next = $map[$startY][$startX];
$pipe = [];
$count = 0;

do {
	$next = findNext($next);
	$map[$next['y']][$next['x']]['visited'] = true;
	$pipe[$next['x'].','.$next['y']] = true;
	$count++;
} while (($next['x'] !== $startX || $next['y'] !== $startY));

// reset all non-pipes to empty
foreach($map as $y => $thing) {
	foreach ($thing as $x => $val) {
		if (!isset($pipe[$x.','.$y])) {
			$map[$y][$x]['char'] = NOTHING;
		} else {
			$map[$y][$x]['visited'] = false;
		}
	}
}

printGrid();

// start walking the pipe, and mark everything to the left/right along the way
$next = $map[$startY][$startX];
$direction = determineDirection($map[$startY][$startX]['char']);
do {
	$next = findNext($next);
	$map[$next['y']][$next['x']]['visited'] = true;

	markSpaces($next, $direction);
	$direction = determineDirection($map[$next['y']][$next['x']]['char'], $direction);

	echo "direction: $direction\n";

} while (($next['x'] !== $startX || $next['y'] !== $startY));


// foreach($map as $y => $thing) {
// 	foreach($thing as $x => $val) {
// 		if ($map[$y][$x]['char'] !== NOTHING) {
// 			// check all
// 		}
// 	}
// }

function markSpaces($cell, $direction)
{
	global $map;

	$x = $cell['x'];
	$y = $cell['y'];

	echo "marking from $x, $y\n";

	$lx = 0;
	$ly = 0;

	$rx = 0;
	$ry = 0;

	if ($direction === R) {
		$ly = -1;
		$ry = 1;
	} else if ($direction === L) {
		$ly = 1;
		$ry = -1;
	} else if ($direction === U) {
		$lx = -1;
		$lx = 1;
	} else if ($direction === D) {
		$lx = 1;
		$lx = -1;
	}

	// mark everything to the left
	$count = 0;
	while (@$map[$y+$ly][$x+$lx]['char'] === NOTHING && $count < 100) {
		$map[$y+$ly][$x+$lx]['left']++;

		$y += $ly;
		$x += $lx;

		echo "marked $x, $y as left\n";

		$count++;
	}

	// reset to the pipe again to mark right
	$x = $cell['x'];
	$y = $cell['y'];

	while (@$map[$y+$ry][$x+$rx]['char'] === NOTHING && $count < 150) {
		$map[$y+$ry][$x+$rx]['right']++;

		$y += $ry;
		$x += $rx;

		echo "marked $x, $y as right\n";

		$count++;
	}

}

// check every cell to the left of each cell to count the # of pipes
// if odd, it's enclosed
/*
$enclosed = 0;
foreach($map as $y => $thing) {
	foreach ($thing as $x => $val) {
		if ($map[$y][$x]['char'] !== NOTHING) {
			continue;
		}

		// check all cells to the left
		// https://www.reddit.com/r/adventofcode/comments/18ey1s7/2023_day_10_part_2_stumped_on_how_to_approach_this/
		// $pipeCountLeft = 0;
		$leftChars = [];
		for($i=$x; $i>=0; $i--) {
			$leftChars[] = 'p'.$map[$y][$i]['char'];
			// if (in_array($map[$y][$i]['char'], POINT_U)) {
			// 	$pipeCountLeft++;
			// }
		}

		// if ($y === 5 && $x === 5) {
			$counts = array_count_values($leftChars);

			$oppositeCounts = @$counts['p'.NE] + @$counts['p'.SW]
				+ @$counts['p'.NW] + @$counts['p'.SE];

			$pipeCountLeft = @$counts['p'.VERT] - ($oppositeCounts / 2);

			// echo '<pre>';
			// var_dump($counts);
			// echo '</pre>';
			// die();

			// echo '<pre>';
			// // var_dump($leftChars);
			// var_dump($counts);
			// // var_dump(@$counts['pL']);
			// // var_dump(@$counts['p'.SE]);
			// // var_dump(@$counts['xL']);
			// // var_dump(@$counts['x7']);
			// // var_dump(@$counts['xL'] + @$counts['x7']);
			// var_dump(@$counts['p'.NE] + @$counts['p'.SW]);
			// var_dump(@$counts['p'.NW] + @$counts['p'.SE]);
			// echo '</pre>';
			// die();

			// define('NE', 'L');
			// define('NW', 'J');
			// define('SW', '7');
			// define('SE', 'F');
		// }

		// $pipeCountRight = 0;
		// for($i=$x; $i<count($map[$y]); $i++) {
		// 	if (in_array($map[$y][$i]['char'], POINT_U) {
		// 		$pipeCountRight++;
		// 	}
		// }

		// if ($pipeCountLeft % 2 === 1) {
		// 	$enclosed++;
		// 	$map[$y][$x]['newchar'] = 'X';
		// 	echo "$x,$y enclosed\n";
		// }
	}
}
*/

printGrid();

$left = 0;
$right = 0;
foreach($map as $y => $thing) {
	foreach($thing as $x => $val) {
		if ($val['left'] > 0) {
			$left++;
		} else if ($val['right'] > 0) {
			$right++;
		}
	}
}

echo '<pre>';
var_dump($left);
var_dump($right);
echo '</pre>';
die();

// 389 too low
// 592 too high
echo '<pre>';
var_dump($enclosed);
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

function determineDirection($char, $direction = '') {
	if ($direction !== '' && ($char === HORIZ || $char === VERT)) {
		return $direction;
	} else if (in_array($char, POINT_U)) {
		return U;
	} else if (in_array($char, POINT_R)) {
		return R;
	} else if (in_array($char, POINT_D)) {
		return D;
	} else if (in_array($char, POINT_L)) {
		return L;
	}
}

function printGrid()
{
	global $map;
	foreach($map as $y => $thing) {
		foreach ($thing as $x => $val) {
			echo $val['char'];
		}

		echo "\n";
	}
}