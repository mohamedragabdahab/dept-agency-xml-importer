<?php

namespace App\Import;

interface ProductImporterInterface
{
    /**
     * @param string $filePath
     *
     * @return void
     */
    public function import(string $filePath): void;
}
