<?php

namespace LisDev\Services;

class PreparationDataService
{
    /**
     * @var string Format of returned data - array, json, xml
     */
    protected string $format = 'array';

    /**
     * Prepare data before return it.
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function prepare(array $data): mixed
    {
        // Returns array
        if ('array' == $this->format) {
            $result = is_array($data)
                ? $data
                : json_decode($data, true);
            // If error exists, throw Exception
            if ($this->throwErrors and array_key_exists('errors', $result) and $result['errors']) {
                throw new \Exception(is_array($result['errors']) ? implode("\n", $result['errors']) : $result['errors']);
            }
            return $result;
        }
        // Returns json or xml document
        return $data;
    }
}