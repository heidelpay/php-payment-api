<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/lib')
    ->in(__DIR__.'/tests')
    ->notPath('/_support/')
    ->notPath('/_data/')
    ->notPath('/_output/');

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules(
        array(
            '@PSR2' => true,
            'no_blank_lines_after_phpdoc' => true,
            'phpdoc_add_missing_param_annotation' => true,
            'native_function_casing' => true,
            'method_separation' => true,
            'method_argument_space' => true,
            'no_empty_statement' => true,
            'no_leading_import_slash' => true,
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_multiline_whitespace_before_semicolons' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_around_offset' => true,
            'no_trailing_comma_in_singleline_array' => true,
            'no_unused_imports' => true,
            'normalize_index_brace' => true,
            'phpdoc_align' => true,
            'phpdoc_separation' => true,
        )
    )
    ->setFinder($finder);