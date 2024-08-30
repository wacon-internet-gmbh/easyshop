<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Easyshop',
    'description' => 'TYPO3 Extension to create a simple shop with paypal express checkout only.',
    'category' => 'plugin',
    'author' => 'Kevin Chileong Lee',
    'author_email' => 'info@wacon.de',
    'author_company' => 'WACON Internet GmbH',
    'state' => 'stable',
    'version' => '0.4.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
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
