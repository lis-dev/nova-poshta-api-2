<?php

namespace LisDev\Services;

use LisDev\Controllers\NovaPoshtaApi2;

class ConnectionService
{
    /**
     * @var string Connection type (curl | file_get_contents)
     */
    protected string $connectionType = 'curl';

    /**
     * Getter for $connectionType property.
     *
     * @return string
     */
    public function getConnectionType(): string
    {
        return $this->connectionType;
    }

    /**
     * Setter for $connectionType property.
     *
     * @param string $connectionType Connection type (curl | file_get_contents)
     *
     * @return $this
     */
    public function setConnectionType(string $connectionType): ConnectionService
    {
        $this->connectionType = $connectionType;
        return $this;
    }
}