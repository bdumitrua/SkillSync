<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

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
        $entitiesIds = $collection->pluck($entityFieldKey)->unique()->all();
        $entitysData = $entityRepository->getByIds($entitiesIds);

        foreach ($collection as $item) {
            $item->{$entityDataKey} = $entitysData->where('id', $item->{$entityFieldKey})->first();
        }
    }
}
