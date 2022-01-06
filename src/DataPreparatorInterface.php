<?php

declare(strict_types=1);

namespace LisDev;

interface DataPreparatorInterface
{
    public function prepare(bool|array|string $data, DataFormat $format, bool $throwErrors);
}