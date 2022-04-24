<?php

namespace LisDev\Interfaces;

use LisDev\Models\Model;

interface ModelInterface
{
    public function save(array $params): Model;
    public function update(array $params): Model;
    public function delete(array $params): string;
}