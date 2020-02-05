<?php

namespace App\Import\Transformer;

use App\Import\Model\ProductProcessing;
use App\Import\Model\VariantProcessing;

class DataProcessing
{
    protected $productProcessing;
    protected $variantProcessing;

    public function __construct(ProductProcessing $productProcessing, VariantProcessing $variantProcessing)
    {
        $this->productProcessing = $productProcessing;
        $this->variantProcessing = $variantProcessing;
    }

    public function process(array $productData): void
    {
        foreach ($productData as $sku => $product) {
            foreach ($product['variants'] as $color => $variants) {
                $sku = sprintf('%s-%s', $sku, $color);
                $productEntity = $this->productProcessing->createProduct($sku, $product['name'], $color);
                $this->variantProcessing->processVariants($variants['sizes'], $product['group'], $productEntity);
            }
        }
    }
}