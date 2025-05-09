<?php

declare(strict_types=1);

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

 use Wacon\Easyshop\Domain\Model\Category;
 use Wacon\Easyshop\Domain\Model\FileReference;

 return [
    FileReference::class => [
        'tableName' => 'sys_file_reference',
    ],
    Category::class => [
        'tableName' => 'sys_category',
        'properties' => [
            'sorting' => [
                'fieldName' => 'sorting',
            ],
        ],
    ],
];
