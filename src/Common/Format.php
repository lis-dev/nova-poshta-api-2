<?php

namespace LisDev\Common;

use LisDev\Controllers\NovaPoshtaApi2;

class Format
{
    public string $format;
    /**
     * Setter for format property.
     *
     * @param string $format Format of returned data by methods (json, xml, array)
     *
     * @return NovaPoshtaApi2
     */
    public function setFormat($format): Format
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Getter for format property.
     *
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}