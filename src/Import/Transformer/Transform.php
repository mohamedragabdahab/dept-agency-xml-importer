<?php

namespace App\Import\Transformer;

class Transform
{
    private $productGroups;
    private $products;

    public function __construct(array $data)
    {
        $this->productGroups = $data['product-groups'];
        $this->products = $data['products'];
    }

    public function init()
    {

        $productsData = [];

        foreach ($this->products['product'] as $product) {
            $sku = isset($product['@attributes']['number']) ? $product['@attributes']['number'] : 'sku';
            $productName = is_string($product['name']) ? $product['name'] : 'name';
            $productsData[$sku]['name'] = $productName;
            $productsData[$sku]['group'] = isset($product['product-group']) ? $product['product-group'] : 'group';

            $productColors = [];

            foreach ($product['variants'] as $variants) {

                foreach ($variants as $variant) {
                    $color = isset($variant['color']) ? $variant['color'] : 'color';
                    if (!in_array($color, $productColors)) {
                        $productColors[] = $color;
                    }

                    $variantSku = isset($variant['variant-code']) ? $variant['variant-code'] : 'sku';
                    $variantSize = isset($variant['size']) ? $variant['size'] : 'size';

                    $productVariants = [];
                    $productVariants['sku'] = $variantSku;
                    $productVariants['name'] = $productName . ' ' . $color . ' ' . $variantSize;
                    $productVariants['size'] = $variantSize;

                    $productsData[$sku]['variants'][$color][] = $productVariants;
                }

            }

            $productsData[$sku]['colors'] = $productColors;
        }

        return $productsData;
    }

}