<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const MAX_ROUNDS = 10000;

$m = 'Monkey ';
$i = 'Starting items: ';
$o = 'Operation: new = ';
$t = 'Test: divisible by ';
$true = 'If true: throw to monkey ';
$false = 'If false: throw to monkey ';

$monkey = 0;
$items = [];
$operation = '';
$test = 0;
$ifTrue = 0;
$ifFalse = 0;

foreach($lines as $line) {
	if (strpos($line, $m) !== false) {
		$monkey = intval(str_replace($m, '', $line));
	} else if (strpos($line, $i) !== false) {
		$items = explode(', ', str_replace($i, '', $line));
	} else if (strpos($line, $o) !== false) {
		$operation = str_replace($o, '', $line);
	} else if (strpos($line, $t) !== false) {
		$test = intval(str_replace($t, '', $line));
	} else if (strpos($line, $true) !== false) {
		$ifTrue = intval(str_replace($true, '', $line));
	} else if (strpos($line, $false) !== false) {
		$ifFalse = intval(str_replace($false, '', $line));
	} else if ($line === '') {
		$monkeys[$monkey] = new Monkey($items, $operation, $test, $ifTrue, $ifFalse);

		$monkey = 0;
		$items = [];
		$operation = '';
		$test = 0;
		$ifTrue = 0;
		$ifFalse = 0;
	}
}

// determine a common multiple of the mod rules
$mod = 1;
foreach($monkeys as $m) {
	$mod *= $m->test;
}

for($round=1; $round<=MAX_ROUNDS; $round++) {
	foreach($monkeys as $m) {
		while (count($m->items) > 0) {
			$item = array_shift($m->items);

			$item = $m->inspect($item, $mod);

			$test = $m->test($item);

			$monkeys[$test]->items[] = $item;
		}
	}
}

$numItems = [];
foreach($monkeys as $m) {
	$numItems[] = $m->numItemsInspected;
}

rsort($numItems);

var_dump($numItems[0] * $numItems[1]);
die();



class Monkey {
	public $items = [];
	public $operation = "";
	public $test = 0;
	public $isTrue = 0;
	public $isFalse = 0;
	public $numItemsInspected = 0;

	public function __construct($items, $operation, $test, $isTrue, $isFalse) {
		$this->items = $items;
		$this->operation = $operation;
		$this->test = $test;
		$this->isTrue = $isTrue;
		$this->isFalse = $isFalse;
	}

	public function inspect($old, $mod) {
		$this->numItemsInspected++;

		$replace = '$new = ' . str_replace('old', $old, $this->operation) . ';';
		eval($replace);

		$new = $new % $mod;

		return $new;
	}

	public function test($item) {
		return $item % $this->test === 0 ? $this->isTrue : $this->isFalse;
	}
}
