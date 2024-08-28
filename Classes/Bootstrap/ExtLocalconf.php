<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension: easyshop.
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

namespace Wacon\Easyshop\Bootstrap;

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Wacon\Easyshop\Controller\ProductController;

class ExtLocalconf extends Base
{
    /**
     * Does the main class purpose
     */
    public function invoke()
    {
        $this->configurePlugins();
    }

    /**
     * Register new frontend plugins
     * @return void
     */
    private function configurePlugins()
    {
        ExtensionUtility::configurePlugin(
            $this->getExtensionKeyAsNamespace(),
            'ListWithDetail',
            [ProductController::class => 'listwithdetail,detail'],
            [],
        );
    }
}
