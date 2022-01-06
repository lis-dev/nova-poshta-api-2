<?php

declare(strict_types=1);

namespace LisDev;

class ApiDefaultDataPreparator implements DataPreparatorInterface
{

    public function prepare(bool|array|string $data, DataFormat $format, bool $throwErrors): mixed
    {
        // Returns array
        if (DataFormat::Array === $format) {
            $result = is_array($data)
                ? $data
                : json_decode($data, true);
            // If error exists, throw Exception
            if ($throwErrors and array_key_exists('errors', $result) and $result['errors']) {
                throw new \Exception(
                    is_array($result['errors']) ? implode("\n", $result['errors']) : $result['errors']
                );
            }

            return $result;
        }

        // Returns json or xml document
        return $data;
    }
}