<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\ProductVariant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowResultsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ShowResultsCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('show-results');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $table = new Table($output);
        $table->setHeaders(['id', 'sku', 'name', 'color']);
        foreach ($products as $product) {
            $table->addRow([
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'color' => $product->getColor(),
            ]);
        }
        $table->render();

        $productVariants = $this->entityManager->getRepository(ProductVariant::class)->findAll();
        $table = new Table($output);
        $table->setHeaders(['id', 'product_id', 'sku', 'name', 'size']);
        foreach ($productVariants as $productVariant) {
            $table->addRow([
                'id' => $productVariant->getId(),
                'product_id' => $productVariant->getProduct()->getId(),
                'sku' => $productVariant->getSku(),
                'name' => $productVariant->getName(),
                'size' => $productVariant->getSize(),
            ]);
        }
        $table->render();
    }


}