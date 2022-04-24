<?php

namespace LisDev\Services;

class ConnectionService
{
    /**
     * @var string Connection type (curl | file_get_contents)
     */
    protected string $connectionType = 'curl';
}