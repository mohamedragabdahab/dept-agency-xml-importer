<?php

namespace App\Tests\Functional\Import;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Import\ProductImporter;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductImporterTest extends KernelTestCase
{
    /**
     * @return void
     */
    public function testImport()
    {
        self::bootKernel();

        $importer = self::$container->get(ProductImporter::class);
        $importer->import(__DIR__ . '/../../Fixtures/products.xml');

        $entityManager = self::$container->get('doctrine')->getManager();

        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();
        $this->assertCount(5, $products);
        foreach ($products as $product) {
            $this->assertInstanceOf(Product::class, $product);
        }

        $productVariants = $entityManager
            ->getRepository(ProductVariant::class)
            ->findAll();
        $this->assertCount(13, $productVariants);
        foreach ($productVariants as $productVariant) {
            $this->assertInstanceOf(ProductVariant::class, $productVariant);
            $this->assertInstanceOf(Product::class, $productVariant->getProduct());
        }
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $purger = new ORMPurger(self::$container->get('doctrine')->getManager());
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();
    }
}
