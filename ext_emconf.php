<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'viewhelpers by kupix webdesign',
    'description' => 'contains all kupix-viewhelpers and additional tt_content elements as well as rte_ckeditor-definitions and data-processors',
    'category' => 'be',
    'author' => 'Kurt Kunig',
    'author_email' => 'kurt.kunig@kupix.de',
    'shy' => '',
    'dependencies' => '',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => false,
    'modify_tables' => '',
    'clearCacheOnLoad' => true,
    'lockType' => '',
    'author_company' => '',
    'version' => '8.8.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.0.0-9.99.999',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'suggests' => [],
    'autoload' => [
       'psr-4' => [
          'kupix\\qpxviewhelper\\' => 'Classes'
       ],
    ],	
];
