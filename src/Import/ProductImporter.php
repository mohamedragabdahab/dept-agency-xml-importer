<?php

namespace App\Import;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Import\Transformer\Transform;
use App\Library\ConvertXmlToJson;
use Doctrine\ORM\EntityManagerInterface;

class ProductImporter implements ProductImporterInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function import(string $filePath): void
    {

        $data = $this->buildData($filePath);
        $transform = new Transform($data);
        $data = $transform->init();

        foreach ($data as $sku => $item) {
            foreach ($item['colors'] as $color) {
                $product = $this->createProduct($sku, $color, $item);

                $this->createVariants($item['variants'][$color], $product);
            }
        }
    }

    private function buildData(string $filePath): array
    {
        $xmlString = file_get_contents($filePath);
        $convertXmlToJson = new ConvertXmlToJson();
        $data = $convertXmlToJson->convert($xmlString);

        return json_decode($data, true);
    }

    private function createProduct($sku, $color, $i): Product
    {
        $product = new Product();

        $product->setSku($sku . '-' . $color);
        $product->setName($i['name']);
        $product->setColor($color);

        $this->manager->persist($product);
        $this->manager->flush();

        return $product;
    }

    private function createVariants($variants, Product $product): void
    {
        foreach ($variants as $variant) {
            $productVariants = new ProductVariant();
            $productVariants->setSku($product->getSku() . '-' . $variant['size']);
            $productVariants->setName($variant['name']);
            $productVariants->setSize($variant['size']);
            $productVariants->setProduct($product);

            $this->manager->persist($productVariants);
            $this->manager->flush();
        }
    }

}