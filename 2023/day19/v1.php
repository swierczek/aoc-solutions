<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('REJECT', 'R');
define('ACCEPT', 'A');
define('GT', '>');
define('LT', '<');

$workflows = [];
$parts = [];

// parse input
$isWorkflow = true;
foreach($lines as $l) {
	if ($l === '') {
		$isWorkflow = false;
		continue;
	}

	if ($isWorkflow) {
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
	} else {
		$partSplit = explode(',', str_replace(['{', '}'], '', $l));

		$temp = [
			'x' => 0,
			'm' => 0,
			'a' => 0,
			's' => 0,
			'status' => '',
			'sum' => 0,
		];

		foreach($partSplit as $p) {
			$valSplit = explode('=', $p);

			$temp[$valSplit[0]] = $valSplit[1];
		}

		$temp['sum'] = $temp['x'] + $temp['m'] + $temp['a'] + $temp['s'];

		$parts[] = $temp;
	}
}

// process the parts!
$acceptScores = [];
foreach($parts as $key => $p) {
	// echo "new part!\n";

	$fn = 'in'; // parts start at the "in" function

	while ($fn !== REJECT && $fn !== ACCEPT) {
		foreach($workflows[$fn] as $w) {
			// echo "  processing rule\n";

			// if this is the last/default rule, no futher processing is needed
			if ($w['default'] === true) {
				$fn = $w['rule'];
				// echo "  default rule is $fn\n";
				break;
			}

			$val = $p[$w['parameter']]; // part value for this workflow's rule

			if ($w['operator'] === GT) {
				// echo "    op is GT\n";
				if ($val > $w['num']) {
					$fn = $w['rule'];
					// echo "      rule is true, new function $fn\n";
					break;
				} else {
					// echo "      rule is false, next rule\n";
				}
			} else if ($w['operator'] === LT) {
				// echo "    op is LT\n";
				if ($val < $w['num']) {
					$fn = $w['rule'];
					// echo "      rule is true, new function $fn\n";
					break;
				} else {
					// echo "      rule is false, next rule\n";
				}
			}

			if ($fn === ACCEPT || $fn === REJECT) {
				// echo "    fn is accept or reject\n";
				break;
			}
		}
	}

	$parts[$key]['status'] = $fn;

	if ($parts[$key]['status'] === ACCEPT) {
		$acceptScores[] = $p['sum'];
	}
}

echo '<pre>';
var_dump($acceptScores);
var_dump(array_sum($acceptScores));
// var_dump($parts);
echo '</pre>';
die();