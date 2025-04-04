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

namespace Wacon\Easyshop\PageTitle;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Wacon\Easyshop\Bootstrap\Traits\ExtensionTrait;
use Wacon\Easyshop\Domain\Model\Product;

class ProductPageTitleProvider extends AbstractPageTitleProvider
{
    use ExtensionTrait;

    public function setTitle(Product $product): void
    {
        $title = LocalizationUtility::translate('pagetitle.product', $this->extensionKey, [
            'product' => $product->getName(),
        ]);
        $this->title = $title;
    }
}
