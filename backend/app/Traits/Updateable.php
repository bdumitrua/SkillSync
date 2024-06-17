<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\StringHelper;

trait Updateable
{
    /**
     * Update a model from a Data Transfer Object (DTO).
     * 
     * Iterates over the DTO properties,
     * converts property names from camelCase to snake_case, and updates the model.
     * Saves the model and logs the success or failure of the save operation.
     * 
     * @param Model $entity The model to be updated.
     * @param mixed $dto The DTO containing the updated data.
     * 
     * @return bool True if the model was successfully updated, false otherwise.
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

        Log::debug('Successfully updated model from DTO');
        return $saved;
    }
}
