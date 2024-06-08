<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!defined('CACHE_CONSTANTS_DEFINED')) {
            $this->defineCacheTimeConstants();
            $this->defineCacheKeysConstants();

            define('CACHE_CONSTANTS_DEFINED', true);
        }
    }

    private function defineCacheTimeConstants(): void
    {
        /*
        *   Cache time
        */

        $hour = 60 * 60;
        $minute = 60;

        define('CACHE_TIME_USER_DATA', 3 * $hour);
        define('CACHE_TIME_USER_NOTIFICATIONS_DATA', 15 * $minute);

        define('CACHE_TIME_TEAM_DATA', 24 * $hour);
        define('CACHE_TIME_TEAM_USER_MODERATOR', 1 * $hour);
        define('CACHE_TIME_TEAM_LINKS_DATA', 24 * $hour);
        define('CACHE_TIME_TEAM_VACANCY_DATA', 1 * $hour);

        define('CACHE_TIME_POST_DATA', 1 * $hour);

        define('CACHE_TIME_TEAM_TAGS_DATA', 24 * $hour);
        define('CACHE_TIME_USER_TAGS_DATA', 3 * $hour);
        define('CACHE_TIME_POST_TAGS_DATA', 1 * $hour);

        define('CACHE_TIME_TEAM_POST_DATA', 3 * $hour);
        define('CACHE_TIME_USER_POST_DATA', 1 * $hour);

        define('CACHE_TIME_TEAM_VACANCIES_DATA', 3 * $hour);
    }

    private function defineCacheKeysConstants(): void
    {
        /*
        *   Cache keys
        */

        define('CACHE_KEY_USER_DATA', 'user_data:');
        define('CACHE_KEY_USER_NOTIFICATIONS_DATA', 'user_notifications_data:');

        define('CACHE_KEY_TEAM_DATA', 'team_data:');
        // :teamId:userId
        define('CACHE_KEY_TEAM_USER_MODERATOR', 'team_user_moderator_data:');
        // :teamId:true/false - isMember
        define('CACHE_KEY_TEAM_LINKS_DATA', 'team_links_data:');
        define('CACHE_KEY_TEAM_VACANCY_DATA', 'team_vacancy_data:');

        define('CACHE_KEY_POST_DATA', 'post_data:');

        define('CACHE_KEY_TEAM_TAGS_DATA', 'team_tags_data:');
        define('CACHE_KEY_USER_TAGS_DATA', 'user_tags_data:');
        define('CACHE_KEY_POST_TAGS_DATA', 'post_tags_data:');

        define('CACHE_KEY_TEAM_POST_DATA', 'team_post_data:');
        define('CACHE_KEY_USER_POST_DATA', 'user_post_data:');

        define('CACHE_KEY_TEAM_VACANCIES_DATA', 'team_vacancies_data:');
    }
}
