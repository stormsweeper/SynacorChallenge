<?php

$inputs = array_slice($argv, 1);

function compute() {
    global $inputs;
    return $inputs[0] + $inputs[1] * $inputs[2]**2 + $inputs[3]**3 - $inputs[4];
}

while (compute() !== 399) {
    shuffle($inputs);
}

print_r($inputs);