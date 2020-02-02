<?php


namespace App\Import\Transformer;


class ProductTransformer
{
    private $sku;
    private $name;
    private $group;
    private $productVariants;

    public function transform($productData): void
    {
        $this->sku = isset($productData['@attributes']['number']) ? $productData['@attributes']['number'] : 'sku';
        $this->name = is_string($productData['name']) ? $productData['name'] : 'name';
        $this->group = isset($productData['product-group']) ? $productData['product-group'] : 'group';
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getGroup()
    {
        return $this->group;
    }
}