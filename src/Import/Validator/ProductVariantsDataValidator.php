<?php

namespace App\Import\Validator;

class ProductVariantsDataValidator implements DataValidator
{
    public function validate($productData): bool
    {
        if (!is_array($productData)) {
            return false;
        }

        if (!is_string($productData['variant-code'])) {
//            $this->logger->error('Product does not have name....');
            return false;
        }

        if (empty($productData['size'])) {
//            $this->logger->error('Product does not have SKU....');
            return false;
        }
//
        if (!is_string($productData['color'])) {
//            $this->logger->error('Product does not have name....');
            return false;
        }

        return true;
    }
}