<?php

namespace App\Command;

use App\Import\ProductImporterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    /**
     * @var ProductImporterInterface
     */
    private $productImporter;

    /**
     * ImportCommand constructor.
     *
     * @param ProductImporterInterface $productImporter
     */
    public function __construct(ProductImporterInterface $productImporter)
    {
        parent::__construct();
        $this->productImporter = $productImporter;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('import');
        $this->setDescription('Imports a XML file');
        $this->setDefinition([
            new InputArgument('file', InputArgument::REQUIRED, 'Path to file to import'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->productImporter->import($input->getArgument('file'));
    }
}
