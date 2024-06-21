<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Elastic\Elasticsearch\Client;
use App\Models\Post;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['entity_type', 'entity_id']);

            $table->renameColumn('entity_type', 'author_type');
            $table->renameColumn('entity_id', 'author_id');

            $table->index(['author_type', 'author_id']);
        });

        $client = app(Client::class);

        $params = [
            'index' => Post::getESIndex(),
            'body' => [
                'script' => [
                    'source' => 'ctx._source.author_type = ctx._source.remove("entity_type"); ctx._source.author_id = ctx._source.remove("entity_id");',
                    'lang' => 'painless'
                ]
            ]
        ];

        $client->updateByQuery($params);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['author_type', 'author_id']);

            $table->renameColumn('author_type', 'entity_type');
            $table->renameColumn('author_id', 'entity_id');

            $table->index(['entity_type', 'entity_id']);
        });

        $client = app(Client::class);

        $params = [
            'index' => Post::getESIndex(),
            'body' => [
                'script' => [
                    'source' => 'ctx._source.entity_type = ctx._source.remove("author_type"); ctx._source.entity_id = ctx._source.remove("author_id");',
                    'lang' => 'painless'
                ]
            ]
        ];

        $client->updateByQuery($params);
    }
};
