<?php

//./vendor/bin/php-cs-fixer --dry-run fix -v
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(false)
    ->setRules([
    '@PSR2' => true,
    '@Symfony' => true,
    'yoda_style' => false,
    'array_syntax' => false,
    'no_alternative_syntax' => false,
    'phpdoc_align' => false,
    'concat_space' => false,
    'increment_style' => false,
    'no_whitespace_in_blank_line' => false,
    'phpdoc_to_comment' => false,
    'visibility_required' => ['elements' => ['property', 'method']],
//    'concat_space' => 'one',
    'no_superfluous_phpdoc_tags' => false,
])
    ->setFinder($finder);
