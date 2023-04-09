<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src');

return (new PhpCsFixer\Config())
    ->setRules([
                   '@Symfony' => true,
                   'yoda_style' => false,
                   'concat_space' => false,
               ])
    ->setFinder($finder);
