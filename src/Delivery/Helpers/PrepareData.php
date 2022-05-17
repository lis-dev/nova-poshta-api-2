<?php

namespace LisDev\Delivery\Helpers;

use LisDev\Delivery\Contracts\FormatInterface;

class PrepareData
{
    /**
     * Prepare data before return it.
     *
     * @param string|array $data
     *
     * @return mixed
     */
    public static function prepare($data, $format, $throwErrors)
    {
        // Returns array
        if (FormatInterface::FORMAT_ARRAY == $format) {
            $result = is_array($data)
                ? $data
                : json_decode($data, true);
            // If error exists, throw Exception
            if ($throwErrors and array_key_exists('errors', $result) and $result['errors']) {
                throw new \Exception(is_array($result['errors']) ?
                    implode("\n", $result['errors']) :
                    $result['errors']);
            }
            return $result;
        }
        // Returns json or xml document
        return $data;
    }
}
