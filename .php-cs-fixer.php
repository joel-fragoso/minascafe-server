<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@Symfony' => true,
    '@Symfony:risky' => true,
];

$finder = Finder::create()
    ->in(__DIR__);

$config = (new Config())
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder);

return $config;
