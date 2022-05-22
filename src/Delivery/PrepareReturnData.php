<?php

class PrepareReturnData
{
    /**
     * @var string Format of returned data - array, json, xml
     */
    protected $format = 'array';

    /**
     * @var bool Throw exceptions when in response is error
     */
    protected $throwErrors = false;

    /**
     * Prepare data before return it.
     *
     * @param string|array $data
     *
     * @return mixed
     */
    public function prepare($data)
    {
        // Returns array
  /*      if ('array' == $this->format) {
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
*/
        // Returns json or xml document
        if ($this->format != 'array') return $data;

        $result = is_array($data)
            ? $data
            : json_decode($data, true);
        // If error exists, throw Exception
        if ($this->throwErrors and array_key_exists('errors', $result) and $result['errors']) {
            throw new \Exception(is_array($result['errors']) ? implode("\n", $result['errors']) : $result['errors']);
        }
        return $result;
    }
}