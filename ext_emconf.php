<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Easyshop',
    'description' => 'TYPO3 Extension to create a simple shop with paypal express checkout only.',
    'category' => 'plugin',
    'author' => 'Kevin Chileong Lee',
    'author_email' => 'info@wacon.de',
    'author_company' => 'WACON Internet GmbH',
    'state' => 'stable',
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.1.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Wacon\\Easyshop\\' => 'Classes',
        ],
    ],
];
