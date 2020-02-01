<?php

namespace App\Repository;

use App\Entity\ProductVariant;
use Doctrine\ORM\EntityRepository;

class ProductVariantRepository extends EntityRepository
{
    /**
     * @param string $sku
     *
     * @return ProductVariant|object|null
     */
    public function findOneBySku(string $sku): ?ProductVariant
    {
        return $this->findOneBy(['sku' => $sku]);
    }
}
