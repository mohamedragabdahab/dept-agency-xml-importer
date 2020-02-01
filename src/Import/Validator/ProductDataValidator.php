<?php

namespace App\Import\Validator;

use Symfony\Component\HttpKernel\Log\Logger;

class ProductDataValidator implements DataValidator
{
//    private $logger;
//
//    public function __construct(Logger $logger)
//    {
//        $this->logger = $logger;
//    }

    public function validate($productData): bool
    {
        if (!is_array($productData)) {
            return false;
        }

        if (empty($productData['@attributes']['number'])) {
//            $this->logger->error('Product does not have SKU....');
            return false;
        }

        if (!is_string($productData['name'])) {
//            $this->logger->error('Product does not have name....');
            return false;
        }

        return true;
    }
}