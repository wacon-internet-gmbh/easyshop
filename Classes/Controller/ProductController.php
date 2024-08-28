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

namespace Wacon\Easyshop\Controller;

use Psr\Http\Message\ResponseInterface;

class ProductController extends BaseController
{
    /**
     * Show list and detail view
     * @return ResponseInterface
     */
    public function listwithdetailAction(): ResponseInterface
    {
        $possibleRedirect = $this->forwardToDetailActionWhenRequested();
        if ($possibleRedirect) {
            return $possibleRedirect;
        }

        return $this->htmlResponse();
    }
    /**
     * Show detail view
     * @return ResponseInterface
     */
    public function detailAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }
}
