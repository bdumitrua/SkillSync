<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

trait SetAdditionalData
{
    /**
     * @param Collection $collection
     * @param string $entityFieldKey
     * @param string $entityDataKey
     * @param mixed $entityRepository
     * 
     * @return void
     */
    protected function setCollectionEntityData(
        Collection &$collection,
        string $entityFieldKey,
        string $entityDataKey,
        $entityRepository
    ): void {
        Log::debug("Setting collection entity data", [
            'collection' => $collection->toArray(),
            'entityFieldKey' => $entityFieldKey,
            'entityDataKey' => $entityDataKey
        ]);

        $entitiesIds = $collection->pluck($entityFieldKey)->unique()->all();
        $entitysData = $entityRepository->getByIds($entitiesIds);

        foreach ($collection as $item) {
            $item->{$entityDataKey} = $entitysData->where('id', $item->{$entityFieldKey})->first();
        }

        Log::debug("Succesfully setted collection entity data", [
            'collection' => $collection->toArray(),
            'entityFieldKey' => $entityFieldKey,
            'entityDataKey' => $entityDataKey
        ]);
    }
}
