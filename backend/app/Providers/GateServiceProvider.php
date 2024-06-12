<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GateServiceProvider extends ServiceProvider
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
        if (!defined('GATE_CONSTANTS_DEFINED')) {
            $this->defineGateMethodsConstants();

            define('GATE_CONSTANTS_DEFINED', true);
        }
    }

    private function defineGateMethodsConstants(): void
    {
        /*
        *   Policies
        */

        // Team
        define('UPDATE_TEAM_GATE', 'updateTeam');
        define('DELETE_TEAM_GATE', 'deleteTeam');
        define('MONITOR_TEAM_APPLICATIONS_GATE', 'monitorTeamApplications');
        define('TOUCH_TEAM_VACANCIES_GATE', 'touchTeamVacancies');
        define('TOUCH_TEAM_POSTS_GATE', 'touchTeamPosts');
        define('TOUCH_TEAM_TAGS_GATE', 'touchTeamTags');
        define('TOUCH_TEAM_LINKS_GATE', 'touchTeamLinks');
        define('TOUCH_TEAM_MEMBERS_GATE', 'touchTeamMembers');

        define('VIEW_TEAM_APPLICATIONS_GATE', 'viewTeamApplication');
        define('APPLY_TO_VACANCY_GATE', 'applyToVacancy');
        define('UPDATE_TEAM_APPLICATION_GATE', 'updateTeamApplication');
        define('DELETE_TEAM_APPLICATION_GATE', 'deleteTeamApplication');

        // Post
        define('CREATE_POST_GATE', 'createPost');
        define('UPDATE_POST_GATE', 'updatePost');
        define('DELETE_POST_GATE', 'deletePost');

        define('DELETE_POST_COMMENT_GATE', 'deletePostComment');

        // TAG
        define('CREATE_TAG_GATE', 'createTag');
        define('DELETE_TAG_GATE', 'deleteTag');

        // Subscription
        define('SUBSCRIBE_TO_ENTITY_GATE', 'subscribeToEntity');
        define('UNSUBSCRIBE_FROM_ENTITY_GATE', 'unsubscribeFromEntity');
    }
}
