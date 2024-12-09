<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));


const H = 0;
const T = 9; // index of last knot (i.e. # knots - 1)

$temp = (object) [
	'x' => 0,
	'y' => 0,
];
$k = [];
for($i=H; $i<=T; $i++) {
	$k[] = clone $temp;
}

// key: x,y, val: # visits
$grid = [];
$grid[$k[T]->x.','.$k[T]->y] = 1;

foreach($lines as $line) {
	$move = explode(' ', $line);
	$dir = $move[0];
	$amount = $move[1];

	echo $line . "\n";

	for ($i=0; $i<$amount; $i++) {
		// move the head
		if ($dir == 'R') {
			$k[H]->x++;
		} else if ($dir == 'L') {
			$k[H]->x--;
		} else if ($dir == 'U') {
			$k[H]->y++;
		} else if ($dir == 'D') {
			$k[H]->y--;
		}

		echo 'head moved to '.$k[H]->x.','.$k[H]->y."\n";

		// for every knot, run the same process, but use the previous
		// knot as the reference "head"
		for($j=1; $j<count($k); $j++) {
			moveNextKnot($k[$j-1], $k[$j]);
		}

		// mark this space as visited
		$grid[$k[T]->x.','.$k[T]->y] = 1;

		echo 'tail moved to '.$k[T]->x.','.$k[T]->y."\n";
	}

}

echo '<pre>';
var_dump(count($grid));
echo '</pre>';
die();


function moveNextKnot($h, &$t) {
	// move the tail depending on head position
	if (
		// same position
		($t->x == $h->x && $t->y == $h->y)
		// within 1 in any direction
		|| (abs($t->x - $h->x) <= 1 && abs($t->y - $h->y) <= 1)
	) {
		echo "nothing\n";
		// do nothing, same space
	} else if ($t->y == $h->y) {
		// same row, move horizontally
		if ($t->x == $h->x-2) {
			// tail is trailing sideways by more than 1 space, move right
			echo "move right\n";
			$t->x++;
		} else if ($t->x == $h->x+2) {
			// tail is leading sideways by more than 1 space, move left
			echo "move left\n";
			$t->x--;
		}
	} else if ($t->x == $h->x) {
		if ($t->y == $h->y-2) {
			// tail is trailing vertically by more than 1 space, move up
			echo "move up\n";
			$t->y++;
		} else if ($t->y == $h->y+2) {
			// tail is leading vertically by more than 1 space, move down
			echo "move down\n";
			$t->y--;
		}
	} else {
		echo "diagonal\n";

		// diagonal
		if ($t->x <= $h->x-1) {
			echo "d right\n";
			$t->x++;

			if ($t->y <= $h->y-1) {
				echo "d up\n";
				$t->y++;
			} else if ($t->y >= $h->y+1) {
				echo "d down\n";
				$t->y--;
			}
		} else if ($t->x >= $h->x+1) {
			echo "d left\n";
			$t->x--;

			if ($t->y <= $h->y-1) {
				echo "d up\n";
				$t->y++;
			} else if ($t->y >= $h->y+1) {
				echo "d down\n";
				$t->y--;
			}
		}
	}
}
