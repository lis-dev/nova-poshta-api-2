<?php

namespace LisDev\Delivery\Contracts;

interface RequestInterface
{
    public const CONNECTION_TYPE_CURL = 'curl';
    public const CONNECTION_TYPE_FILE_GET_CONTENTS = 'file_get_contents';

    public function exec(string $model, string $method, ?array $params = null);
}
