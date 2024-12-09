<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$pairs = [];
$index = 0;
$left = null;
$right = null;

$correct = [];
$incorrect = [];
foreach($lines as $line) {
	if ($line == '') {
		$index++;
		continue;
	}

	if ($left == null) {
		$left = json_decode($line, true);
	} else {
		$right = json_decode($line, true);

		$test = checkPacket($left, $right);

		echo '<pre>';
		var_dump($test);
		echo '</pre>';
		die();
	}
}

echo '<pre>';
var_dump($correct);
var_dump($incorrect);
echo '</pre>';
die();

function checkPacket($l, $r) {
	// both ints, compare them
	if (is_int($l) && is_int($r)) {
		echo "comparing ints $l to $r\n";
		return $l <= $r;

	// if l is shorter, it's sorted
	} else if ($l == null && $r != null) {
		return true;

	// if r is shorter, it's not sorted
	} else if ($l != null && $r == null) {
		return false;

	// if one or the other are arrays, convert to arrays and iterate deeper
	} else if (is_array($l) && !is_array($r)) {
		echo "running again with r converted to array\n";
		return checkPacket($l, [$r]);
	} else if (!is_array($l) && is_array($r)) {
		echo "running again with l converted to array\n";
		return checkPacket([$l], $r);

	// if both are arrays, check the length, or iterate deeper
	} else if (is_array($l) && is_array($r)) {
		// echo '<pre>';
		// var_dump($l);
		// var_dump($r);
		// echo '</pre>';
		// die();
		$maxLength = max(count($l), count($r));

		for($key=0; $key<$maxLength; $key++) {
			$test = checkPacket($l[$key], $r[$key]);
			if (!$test) {
				return false;
			}
		}

		echo "end\n";

		return true;
	} else {
		echo "wtfffffffffffffff\n";
	}
}
