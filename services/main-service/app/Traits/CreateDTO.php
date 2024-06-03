<?php

namespace App\Traits;

use App\Exceptions\UnprocessableContentException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait CreateDTO
{
    /**
     * @param Request $request
     * @param string $dtoClass
     * 
     * @return mixed
     */
    protected function createDTO(Request $request, string $dtoClass)
    {
        Log::debug('Started createDTO', [
            'requestType' => get_class($request),
            'dtoClass' => $dtoClass
        ]);
        $filteredRequestData = array_filter($request->all());
        if (empty($filteredRequestData)) {
            throw new UnprocessableContentException('At least one field must be filled');
        }

        Log::debug('Creating DTO from request');
        $dto = new $dtoClass;
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
