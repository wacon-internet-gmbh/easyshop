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

namespace Wacon\Easyshop\Domain\Model;

class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category
{
    /**
     * sorting
     * @var int
     */
    protected int $sorting = 0;

    /**
     * Get sorting
     *
     * @return int
     */
    public function getSorting(): int
    {
        return $this->sorting;
    }

    /**
     * Set sorting
     *
     * @param int  $sorting
     *
     * @return self
     */
    public function setSorting(int $sorting): self
    {
        $this->sorting = $sorting;

        return $this;
    }
}
