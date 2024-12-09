# Initial setup

Clone the `aoc-inputs/` repo alongside this one to keep the puzzle inputs private.

# New year setup

Copy the `YYYY-template` and rename it to the current year. This contains separate directories for each day, with a template for `v1.php` and a blank `v2.php`.

# Daily setup

Go to directory:

`cd ~/Documents/aoc-solutions/2024/day##`

Copy this day's test input and real input into this day's input files in `aoc-inputs/`.

Run v1 with test input:

`php v1.php`

Run v1 with real input:

`php v1.php input.txt`

After solving v1, copy/paste code into v2.php and run

`php v2.php`

# Note for solutions before 2024 day 8

Inputs have been moved as of 2024 day 8. So running any solution file prior to that likely needs to be adjusted to read from the correct new input file. Adding the following to the top of any solution file (replacing the current `$filename` line) will allow the correct input file to be read.

```
$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');
```
