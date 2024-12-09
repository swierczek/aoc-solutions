<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const GROOVE_1 = 1000;
const GROOVE_2 = 2000;
const GROOVE_3 = 3000;

$order = [];
foreach($lines as $line) {
	$order[] = intval($line);
}

$file = $order;
$length = count($file); // we'll do % $length a bunch here...

foreach($order as $key => $val) {
	echo '<pre>';
	var_dump($key);
	var_dump($val);
	echo '</pre>';
	die();
}
