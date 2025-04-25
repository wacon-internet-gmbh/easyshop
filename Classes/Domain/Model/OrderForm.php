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

use ReflectionClass;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class OrderForm
{
    /**
     * Product that is ordered
     * @var Product
     */
    protected Product $product;

    /**
     * firstname
     * @var string
     */
    protected string $firstname = '';

    /**
     * lastname
     * @var string
     */
    protected string $lastname = '';

    /**
     * street
     * @var string
     */
    protected string $street = '';

    /**
     * postcode
     * @var string
     */
    protected string $postcode = '';

    /**
     * city
     * @var string
     */
    protected string $city = '';

    /**
     * country
     * @var string
     */
    protected string $country = '';

    /**
     * Get product that is ordered
     *
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Set product that is ordered
     *
     * @param Product  $product
     *
     * @return self
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Set firstname
     *
     * @param string  $firstname
     *
     * @return self
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = \trim($firstname);

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Set lastname
     *
     * @param string  $lastname
     *
     * @return self
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = \trim($lastname);

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Set street
     *
     * @param string  $street
     *
     * @return self
     */
    public function setStreet(string $street): self
    {
        $this->street = \trim($street);

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * Set postcode
     *
     * @param string  $postcode
     *
     * @return self
     */
    public function setPostcode(string $postcode): self
    {
        $this->postcode = \trim($postcode);

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param string  $city
     *
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = \trim($city);

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param string  $country
     *
     * @return self
     */
    public function setCountry(string $country): self
    {
        $this->country = \trim($country);

        return $this;
    }

    /**
     * Check if model is empty
     * @return bool
     */
    public function isEmpty(): bool
    {
        $reflectionClass = new ReflectionClass(self::class);
        $allProperties = $reflectionClass->getProperties();
        $propertiesToIgnore = ['product', 'country'];

        foreach($allProperties as $property) {
            $propertyName = $property->getName();

            if (\in_array($propertyName, $propertiesToIgnore)) {
                continue;
            }

            if (\trim($this->$propertyName) != '') {
                return false;
            }
        }

        return true;
    }

    /**
     * Wrapper to access isEmpty in fluid
     * @return bool
     */
    public function getIsEmpty(): bool
    {
        return $this->isEmpty();
    }

    /**
     * Export object to store in session
     * with the goal to restore it with importFromSession
     * @return array
     */
    public function exportForSession(): array
    {
        $reflectionClass = new ReflectionClass(self::class);
        $allProperties = $reflectionClass->getProperties();
        $exportData = [];

        foreach ($allProperties as $property) {
            $propertyName = $property->getName();

            if ($this->$propertyName instanceof AbstractEntity) {
                $exportData[$propertyName] = $this->$propertyName->getUid();
            } else if (!\is_object($this->$propertyName)) {
                $exportData[$propertyName] = $this->$propertyName;
            }
        }

        return $exportData;
    }
}
