<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    /**
     * @param string $sku
     *
     * @return Product|object|null
     */
    public function findOneBySku(string $sku): ?Product
    {
        return $this->findOneBy(['sku' => $sku]);
    }
}
