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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Product extends AbstractEntity
{
    /**
     * Product name
     * @var string
     */
    protected string $name = '';

    /**
     * Product description
     * @var string
     */
    protected string $description = '';

    /**
     * Product details
     * @var string
     */
    protected string $details = '';

    /**
     * Net price
     * @var float
     */
    protected float $netPrice = 0.00;

    /**
     * Gross price
     * @var float
     */
    protected float $grossPrice = 0.00;

    /**
     * VAT
     * @var float
     */
    protected float $vat = 19.00;

    /**
     * Currency
     * @var string
     */
    protected string $currency = 'EUR';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Wacon\Easyshop\Domain\Model\Category>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $categories;

    /**
     * Summary of image
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Wacon\Easyshop\Domain\Model\FileReference>
     */
    protected $images;

    /**
     * Initialize relation objects
     */
    public function __construct()
    {
        $this->categories = new ObjectStorage();
    }

    /**
     * Set categories
     *
     * @param  ObjectStorage<\Wacon\Easyshop\Domain\Model\Category> $categories
     */
    public function setCategories($categories): void
    {
        $this->categories = $categories;
    }

    /**
     * Adds a category to this categories.
     *
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        $this->getCategories()->attach($category);
    }

    /**
     * Get categories
     *
     * @return ObjectStorage<\Wacon\Easyshop\Domain\Model\Category>|null
     */
    public function getCategories(): ?ObjectStorage
    {
        return $this->categories;
    }

    /**
     * Get product name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set product name
     *
     * @param  string  $name  Product name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get product description
     *
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set product description
     *
     * @param  string  $description  Product description
     *
     * @return  self
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get product details
     *
     * @return  string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set product details
     *
     * @param  string  $details  Product details
     *
     * @return  self
     */
    public function setDetails(string $details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get net price
     *
     * @return  float
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * Set net price
     *
     * @param  float  $netPrice  Net price
     *
     * @return  self
     */
    public function setNetPrice(float $netPrice)
    {
        $this->netPrice = $netPrice;

        return $this;
    }

    /**
     * Get gross price
     *
     * @return  float
     */
    public function getGrossPrice()
    {
        $this->calculateGrossPrice();
        return $this->grossPrice;
    }

    /**
     * Set gross price
     *
     * @param  float  $grossPrice  Gross price
     *
     * @return  self
     */
    public function setGrossPrice(float $grossPrice)
    {
        $this->grossPrice = $grossPrice;

        return $this;
    }

    /**
     * Get vAT
     *
     * @return  float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Get vAT
     *
     * @return  float
     */
    public function getVatInCurrency()
    {
        return \round(($this->vat * $this->netPrice) / 100, 2);
    }

    /**
     * Set vAT
     *
     * @param  float  $vat  VAT
     *
     * @return  self
     */
    public function setVat(float $vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get currency
     *
     * @return  string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currency
     *
     * @param  string  $currency  Currency
     *
     * @return  self
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function calculateGrossPrice()
    {
        $this->grossPrice = round(($this->netPrice * (1 + ($this->vat / 100))), 2);
    }

    /**
     * Get summary of image
     *
     * @return  \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Wacon\Easyshop\Domain\Model\FileReference>
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Return the first image
     * @return FileReference
     */
    public function getFirstImage(): ?FileReference
    {
        return $this->images->count() > 0 ? $this->images->offsetGet(0) : null;
    }

    /**
     * Set summary of image
     *
     * @param  \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Wacon\Easyshop\Domain\Model\FileReference>  $images  Summary of image
     *
     * @return  self
     */
    public function setImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Return the data for the paypal cart of this product
     * @return string
     */
    public function getPaypalCartData(): string
    {
        return \json_encode([
            'id' => $this->getUid(),
        ]);
    }
}
