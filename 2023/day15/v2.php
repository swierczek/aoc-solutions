<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$commands = explode(',', $input);

$boxes = array_fill(0, 255, []);
foreach($commands as $command) {
	$parts = preg_split('/([=-])/', $command, -1, PREG_SPLIT_DELIM_CAPTURE);

	$label = $parts[0];
	$op = $parts[1];

	$boxNum = getHash($label); // 0-255

	if ($op === '-') {
		unset($boxes[$boxNum][$label]);
	} else if ($op === '=') {
		$num = $parts[2];

		$boxes[$boxNum][$label] = $num;
	}
}


$powers = [];
foreach($boxes as $i => $lenses) {
	$slot = 1;

	foreach($lenses as $label => $length) {
		$power = 1 + $i;
		$power *= $slot++;
		$power *= $length;

		$powers[] = $power;
	}
}

echo '<pre>';
var_dump(array_sum($powers));
echo '</pre>';
die();

function getHash($command)
{
	$chars = str_split($command);
	$value = 0;

	foreach($chars as $c) {
		$value += ord($c);
		$value *= 17;
		$value = $value % 256;
	}

	return $value;
}
