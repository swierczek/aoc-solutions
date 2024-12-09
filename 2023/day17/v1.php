<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$map = [];
foreach($lines as $l) {
	$map[] =str_split($l);
}

echo '<pre>';
var_dump('');
echo '</pre>';
die();