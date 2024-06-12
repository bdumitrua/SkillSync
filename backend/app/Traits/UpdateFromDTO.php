<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\StringHelper;

trait UpdateFromDTO
{
    /**
     * @param Model $entity
     * @param mixed $dto
     * 
     * @return bool
     */
    public function updateFromDto(Model $entity, $dto): bool
    {
        Log::debug('Updating model from DTO', [
            'modelType' => get_class($entity),
            'modelData' => $entity->toArray(),
            'dtoType' => get_class($dto),
            'dtoData' => $dto->toArray()
        ]);

        $dtoProperties = get_object_vars($dto);
        foreach ($dtoProperties as $property => $value) {
            $property = StringHelper::camelToSnake($property);
            $entity->$property = $value;
        }

        $saved = $entity->save();

        if (!$saved) {
            Log::debug("Couldn't update model from DTO");
            return $saved;
        }

        Log::debug('Succesfully updated model from DTO');
        return $saved;
    }
}
