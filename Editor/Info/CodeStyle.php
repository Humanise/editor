<?php
$exp = explode('/', __DIR__);
$dir = implode('/', array_splice($exp,0,-2));

$finder = PhpCsFixer\Finder::create()
  ->exclude(['Editor/Libraries', 'node_modules', 'hui'])
  ->in($dir)
;

return PhpCsFixer\Config::create()
  ->setRules([
    'space_after_semicolon' => true,
    'whitespace_after_comma_in_array' => true,
    'binary_operator_spaces' => ['align_equals' => false],
    'concat_space' => ['spacing' => 'one'],
    'array_syntax' => ['syntax' => 'short']
  ])
  ->setFinder($finder)
;