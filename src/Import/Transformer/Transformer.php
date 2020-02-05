<?php

namespace App\Import\Transformer;

class Transformer
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function transform(): array
    {
        $result = [];
        $products = $this->data['products'];
        foreach ($products as $product) {

            foreach ($product as $productData) {

                if (!$this->isValidProduct($productData)) {
                    continue;
                }

                $sku = $productData['@attributes']['number'];
                $result[$sku]['name'] = $productData['name'];
                $result[$sku]['group'] = $productData['product-group'];

                $variants = [];

                foreach ($productData['variants'] as $variant) {

                    if (isset($variant['color'])) {
                        $color = $variant['color'];
                        $variants[$color]['sizes'][] = $variant['size'];
                        continue;
                    }

                    foreach ($variant as $variantItem) {
                        $color = $variantItem['color'];
                        $variants[$color]['sizes'][] = $variantItem['size'];
                    }

                }

                $result[$sku]['variants'] = $variants;
            }
        }

        return $result;
    }

    private function isValidProduct(array $productData): bool
    {
        return !(empty($productData['@attributes']['number']) || empty($productData['variants']));
    }
}