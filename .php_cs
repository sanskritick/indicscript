<?php
$excluded_folders = [
    'node_modules',
    'storage',
    'vendor',
    'public',
];
$finder = PhpCsFixer\Finder::create()
    ->exclude($excluded_folders)
    ->notName('AcceptanceTester.php')
    ->notName('FunctionalTester.php')
    ->notName('UnitTester.php')
    ->in(__DIR__);
;
return PhpCsFixer\Config::create()
    ->setRules(array(
        '@Symfony' => true,
        'binary_operator_spaces' => ['align_double_arrow' => true, 'align_equals' => true],
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'ordered_imports' => ['sort_algorithm' => 'length'],
        'phpdoc_order' => true,
        'phpdoc_no_empty_return' => false,
        'phpdoc_trim' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true
        ],
        'concat_space' => ['spacing' => 'one'],
        'blank_line_before_statement' => ['statements' => ['return']],
        'not_operator_with_successor_space' => true,
        'braces' => [
            'allow_single_line_closure' => false,
            'position_after_control_structures' => 'same',
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_anonymous_constructs' => 'same'
        ],
        'yoda_style' => false
    ))
    ->setFinder($finder)
;
