<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src');

return (new PhpCsFixer\Config())
    ->setRules([
                   '@Symfony' => true,
                   'yoda_style' => false,
                   'concat_space' => false,
                   'declare_strict_types' => true,
                   'strict_comparison' => true,
                   'trailing_comma_in_multiline' => true,
               ])
    ->setFinder($finder);
