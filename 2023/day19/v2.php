<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('REJECT', 'R');
define('ACCEPT', 'A');
define('GT', '>');
define('LT', '<');

$workflows = [];

// parse input
foreach($lines as $l) {
	if ($l === '') {
		break;
	}

	$split = explode('{', str_replace('}', '', $l));
	$name = $split[0];

	$functions = explode(',', $split[1]);

	$fns = [];
	foreach($functions as $key => $f) {
		$temp = [
			'default' => false,
			'parameter' => '',
			'operator' => '',
			'num' => 0,
			'rule' => '',
		];

		if ($key < count($functions)-1) {
			// a<2006:qkq
			// a>1716:R
			// rfg
			$ruleSplit = explode(':', $f);

			$temp['rule'] = $ruleSplit[1];

			$operator = '';

			if (stripos($ruleSplit[0], GT) !== false) {
				$operator = GT;
				$paramSplit = explode(GT, $ruleSplit[0]);
			} else if (stripos($ruleSplit[0], LT) !== false) {
				$operator = LT;
				$paramSplit = explode(LT, $ruleSplit[0]);
			} else {
				echo '<pre>';
				var_dump('wtf');
				var_dump($f);
				echo '</pre>';
				die();
			}

			$temp['operator'] = $operator;
			$temp['parameter'] = $paramSplit[0];
			$temp['num'] = intval($paramSplit[1]);

		} else {
			// always ends in a standalone rule
			$temp['rule'] = $f;
			$temp['default'] = true;
		}

		$fns[] = $temp;
	}

	$workflows[$name] = $fns;
}

foreach($workflows as $w) {

}