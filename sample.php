<?php
header('Content-Type: text/plain; charset=utf-8');
function greet($name) {
	return "Hello, " . $name . "!";
}
$sum = 0;
for ($i = 1; $i <= 5; $i++) { $sum += $i; }
echo greet('世界') . "\n";
echo "Sum= " . $sum . "\n";