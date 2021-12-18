<?php

namespace App\Services;

interface PinService
{
    public function generate(int $number): array;
}
