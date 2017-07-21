<?php

$finder = PhpCsFixer\Finder::create()
  ->exclude(['/Editor/Libraries','/node_modules','/hui'])
  ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        'space_after_semicolon' => true,
        'array_syntax' => ['syntax' => 'short']
    ])
    ->setFinder($finder)
;