<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const MINS = 24;
const REGEX = '/Blueprint (\d+): Each ore robot costs (\d+) ore. Each clay robot costs (\d+) ore. Each obsidian robot costs (\d+) ore and (\d+) clay. Each geode robot costs (\d+) ore and (\d+) obsidian./';

$blueprints = [];
foreach($lines as $line) {
	preg_match(REGEX, $line, $matches);

	$blueprints[intval($matches[1])] = (object) [
		'ore_robot_cost_ore' => intval($matches[2]),
		'clay_robot_cost_ore' => intval($matches[3]),
		'obsidian_robot_cost_ore' => intval($matches[4]),
		'obsidian_robot_cost_clay' => intval($matches[5]),
		'geode_robot_cost_ore' => intval($matches[6]),
		'geode_robot_cost_obsidian' => intval($matches[7]),

		'ore_robots' => 1,
		'clay_robots' => 0,
		'obsidian_robots' => 0,
		'geode_robots' => 0,

		'ore' => 0,
		'clay' => 0,
		'obsidian' => 0,
		'geode' => 0,
	];
}

foreach($blueprints as $key => $b) {

	for($i=1; $i<=MINS; $i++) {
		echo "== Minute $i ==\n";

		$geode_robot_created = 0;
		$obsidian_robot_created = 0;
		$clay_robot_created = 0;
		$ore_robot_created = 0;

		// idk what these do...
		$max_cost_ore = max($b->ore_robot_cost_ore, $b->clay_robot_cost_ore,
                       $b->obsidian_robot_cost_ore, $b->geode_robot_cost_ore);
		$max_cost_clay = $b->obsidian_robot_cost_clay;
		$max_cost_obsidian = $b->geode_robot_cost_obsidian;

		// build robots (worst to best)

		// ore robot
		if ($b->ore_robot_cost_ore <= $b->ore && $b->ore_robots < $max_cost_ore) {
			$b->ore -= $b->ore_robot_cost_ore;

			$b->ore_robots++;

			$ore_robot_created = 1;

			echo "Spend $b->ore_robot_cost_ore ore to start building an ore-collecting robot\n";


		// clay robot
		} else if (
			$b->clay_robot_cost_ore <= $b->ore
			&& $b->clay_robots < $max_cost_clay
		) {
			$b->ore -= $b->clay_robot_cost_ore;

			$b->clay_robots++;

			$clay_robot_created = 1;

			echo "Spend $b->clay_robot_cost_ore ore to start building a clay-collecting robot\n";


		// obsidian robot
		} else if (
			$b->obsidian_robot_cost_ore <= $b->ore
			&& $b->obsidian_robot_cost_clay <= $b->clay
			&& $b->obsidian_robots < $max_cost_obsidian
		) {
			$b->ore -= $b->obsidian_robot_cost_ore;
			$b->clay -= $b->obsidian_robot_cost_clay;

			$b->obsidian_robots++;

			$obsidian_robot_created = 1;

			echo "Spend $b->obsidian_robot_cost_ore ore and $b->obsidian_robot_cost_clay clay to start building a obsidian-collecting robot\n";


		// geode robot
		} else if (
			$b->geode_robot_cost_ore <= $b->ore
			&& $b->geode_robot_cost_obsidian <= $b->obsidian
		) {
			$b->ore -= $b->geode_robot_cost_ore;
			$b->obsidian -= $b->geode_robot_cost_obsidian;

			$b->geode_robots++;

			$geode_robot_created = 1;

			echo "Spend $b->geode_robot_cost_ore ore and $b->geode_robot_cost_obsidian obsidian to start building a geode-cracking robot\n";
		}

		// then collect materials

		$new_ore = $b->ore_robots - $ore_robot_created;
		$new_clay = $b->clay_robots - $clay_robot_created;
		$new_obsidian = $b->obsidian_robots - $obsidian_robot_created;
		$new_geode = $b->geode_robots - $geode_robot_created;

		$b->ore += $new_ore;
		$b->clay += $new_clay;
		$b->obsidian += $new_obsidian;
		$b->geode += $new_geode;

		echo "$new_ore ore-collecting robots collect $new_ore ore; you now have $b->ore ore.\n";
		echo "$new_clay clay-collecting robots collect $new_clay clay; you now have $b->clay clay.\n";
		echo "$new_obsidian obsidian-collecting robots collect $new_obsidian obsidian; you now have $b->obsidian obsidian.\n";
		echo "$new_geode geode-cracking robots collect $new_geode geodes; you now have $b->geode geodes.\n";

		// echo "ending minute $i\n\n";
		echo "\n";

		// update the source array with new values
		$blueprints[$key] = $b;
	}

	echo "blueprint $key created ".$blueprints[$key]->geode." geodes\n";

	echo '<pre>';
	var_dump($blueprints);
	echo '</pre>';
	die();
}