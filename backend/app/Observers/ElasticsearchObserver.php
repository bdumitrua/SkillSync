<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Elasticsearchable;

class ElasticsearchObserver
{
    /**
     * Handle the "created" event.
     *
     * @param  Model  $model
     * @return void
     */
    public function created(Model $model)
    {
        if (in_array(Elasticsearchable::class, class_uses($model))) {
            Log::debug('Indexing new model to elasticsearch', [
                'model' => $model?->toSearchableArray() ?? null
            ]);

            $model::addToElasticsearch($model);
        }
    }

    /**
     * Handle the "updated" event.
     *
     * @param  Model  $model
     * @return void
     */
    public function updated(Model $model)
    {
        if (in_array(Elasticsearchable::class, class_uses($model))) {
            Log::debug('Updating model to elasticsearch', [
                'model' => $model?->toSearchableArray() ?? null
            ]);

            $model::updateElasticsearchDocument($model);
        }
    }

    /**
     * Handle the "deleted" event.
     *
     * @param  Model  $model
     * @return void
     */
    public function deleted(Model $model)
    {
        if (in_array(Elasticsearchable::class, class_uses($model))) {
            Log::debug('Deleting model from elasticsearch', [
                'model' => $model->id
            ]);

            $model::deleteElasticsearchDocument($model->id);
        }
    }
}
