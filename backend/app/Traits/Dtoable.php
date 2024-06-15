<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use App\Exceptions\UnprocessableContentException;

trait Dtoable
{
    public function createDTO()
    {
        Log::debug('Started createDTO', [
            'requestType' => get_class($this),
            'dtoClass' => $this->dtoClass
        ]);
        $filteredRequestData = array_filter($this->all());
        if (empty($filteredRequestData)) {
            throw new UnprocessableContentException('At least one field must be filled');
        }

        Log::debug('Creating DTO from request');
        $dto = new $this->dtoClass;
        foreach ($filteredRequestData as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }

        Log::debug('Created DTO from request', [
            'DTO data' => $dto->toArray()
        ]);
        return $dto;
    }
}
