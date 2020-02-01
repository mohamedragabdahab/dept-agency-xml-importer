<?php

namespace App\Import\Validator;

interface DataValidator
{
    public function validate($data): bool;
}