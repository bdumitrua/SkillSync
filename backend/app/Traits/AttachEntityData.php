<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\IdentifiableRepositoryInterface;

// God forgive me...
trait AttachEntityData
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
        IdentifiableRepositoryInterface $entityRepository
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

    /**
     * @param Collection $morphCollection
     * @param string $morphKey
     * @param string $entityKey
     * @param IdentifiableRepositoryInterface $repository
     * 
     * @return void
     */
    protected function setCollectionMorphData(
        Collection &$morphCollection,
        string $morphKey,
        string $entityKey,
        IdentifiableRepositoryInterface $repository
    ) {
        Log::debug("Setting collection morph data", [
            'collection' => $morphCollection->toArray(),
            'morphKey' => $morphKey,
            'entityKey' => $entityKey
        ]);

        $morphKeyType = $morphKey . '_type';
        $morphKeyId = $morphKey . '_id';
        $morphIds = [];

        foreach ($morphCollection as $model) {
            if ($model->{$morphKeyType} === config('entities.' . $entityKey)) {
                $morphIds[] = $model->{$morphKeyId};
            }
        }

        $morphIds = array_unique($morphIds);
        Log::debug('Quering morph data by morphIds', [
            'morphIds' => $morphIds,
            'entityKey' => $entityKey
        ]);
        $entitiesData = $repository->getByIds($morphIds);

        $morphKeyData = $morphKey . 'Data';
        foreach ($morphCollection as $model) {
            if ($model->{$morphKeyType} === config('entities.' . $entityKey)) {
                $model->{$morphKeyData} = $entitiesData->where('id', $model->{$morphKeyId})->first();
            }
        }

        Log::debug("Succesfully setted collection morph data", [
            'collection' => $morphCollection->toArray(),
            'morphKey' => $morphKey,
            'entityKey' => $entityKey
        ]);
    }
}
