<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));


$h = (object) [
	'x' => 0,
	'y' => 0,
];
$t = clone $h;

// key: x,y, val: # visits
$grid = [];
$grid[$t->x.','.$t->y] = 1;

foreach($lines as $line) {
	$move = explode(' ', $line);
	$dir = $move[0];
	$amount = $move[1];

	echo $line . "\n";

	for ($i=0; $i<$amount; $i++) {
		// move the head
		if ($dir == 'R') {
			$h->x++;
		} else if ($dir == 'L') {
			$h->x--;
		} else if ($dir == 'U') {
			$h->y++;
		} else if ($dir == 'D') {
			$h->y--;
		}

		echo 'head moved to '.$h->x.','.$h->y."\n";

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

		// mark this space as visited
		$grid[$t->x.','.$t->y] = 1;

		echo 'tail moved to '.$t->x.','.$t->y."\n";
	}

}

echo '<pre>';
var_dump(count($grid));
echo '</pre>';
die();
