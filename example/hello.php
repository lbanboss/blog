<?php

echo "Hello from original source!\n";

function greet(string $name): void {
    echo "Hi, {$name}!\n";
}

greet('world');