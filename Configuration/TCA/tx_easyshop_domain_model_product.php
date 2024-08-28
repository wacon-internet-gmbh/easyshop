<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

$imageConfig = $GLOBALS['TCA']['tt_content']['columns']['image'];

return [
    'ctrl' => [
        'title' => 'LLL:EXT:easyshop/Resources/Private/Language/locallang_db.xlf:tx_easyshop_domain_model_product',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'default_sortby' => 'ORDER BY name',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'typeicon_classes' => [
            'default' => 'tx_easyshop_domain_model_product',
        ],
    ],
    'columns' => [
        'name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:easyshop/Resources/Private/Language/locallang_db.xlf:tx_easyshop_domain_model_product.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'required' => true,
                'eval' => 'trim,unique',
            ],
        ],
        'description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:easyshop/Resources/Private/Language/locallang_db.xlf:tx_easyshop_domain_model_product.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'details' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:easyshop/Resources/Private/Language/locallang_db.xlf:tx_easyshop_domain_model_product.details',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'net_price' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:easyshop/Resources/Private/Language/locallang_db.xlf:tx_easyshop_domain_model_product.net_price',
            'config' => [
                'type' => 'number',
                'eval' => 'trim',
                'default' => 0.00,
                'format' => 'decimal',
                'range' => [
                    'lower' => 0.00,
                ],
            ],
        ],
        'gross_price' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:easyshop/Resources/Private/Language/locallang_db.xlf:tx_easyshop_domain_model_product.gross_price',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'vat' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:easyshop/Resources/Private/Language/locallang_db.xlf:tx_easyshop_domain_model_product.vat',
            'config' => [
                'type' => 'number',
                'eval' => 'trim',
                'default' => 19.00,
                'format' => 'decimal',
                'range' => [
                    'lower' => 0.00,
                ],
            ],
        ],
        'currency' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:easyshop/Resources/Private/Language/locallang_db.xlf:tx_easyshop_domain_model_product.currency',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,upper',
                'default' => 'EUR',
                'size' => 3,
                'max' => 3,
                'min' => 3
            ],
        ],
        'categories' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.categories',
            'config' => [
                'type' => 'category',
            ],
        ],
        'images' => $imageConfig
    ],
    'types' => [
        '0' => ['showitem' => 'hidden,name,description,details,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images, images,--div--;LLL:EXT:easyshop/Resources/Private/Language/locallang_ttc.xlf:palette.prices,--palette--;;prices,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories, categories, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, --palette--;;hidden, --palette--;;access']
    ],
    'palettes' => [
        'hidden' => $GLOBALS['TCA']['tt_content']['palettes']['hidden'],
        'access' => $GLOBALS['TCA']['tt_content']['palettes']['access'],
        'prices' => [
            'showitem' => 'net_price,gross_price,vat,currency'
        ]
    ]
];
