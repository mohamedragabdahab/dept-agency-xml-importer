<?php

namespace App\Import\Transformer;

class ProductVariantsTransformer
{
    private $sku;
    private $name;
    private $size;
    private $color;
    private $colorCollection = [];

    public function validate($variantData, ProductTransformer $product)
    {
        $this->sku = isset($variantData['variant-code']) ? $variantData['variant-code'] : 'sku';;
        $this->size = isset($variantData['size']) ? $variantData['size'] : 'size';
        $this->color = isset($variantData['color']) ? $variantData['color'] : 'color';
        if (!in_array($this->color, $this->colorCollection)) {
            $this->colorCollection[] = $this->color;
        }
        $this->name = sprintf('%s %s %s', $product->getName(), $this->color, $this->size);
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getColorCollection(): array
    {
        return $this->colorCollection;
    }
}