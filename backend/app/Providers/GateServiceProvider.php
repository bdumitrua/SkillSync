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
        define('MONITOR_TEAM_APPLICATIONS_GATE', 'monitorTeamApplications');
        define('TOUCH_TEAM_VACANCIES_GATE', 'touchTeamVacancies');
        define('TOUCH_TEAM_POSTS_GATE', 'touchTeamPosts');
        define('TOUCH_TEAM_PROJECTS_GATE', 'touchTeamProjects');
        define('TOUCH_TEAM_TAGS_GATE', 'touchTeamTags');
        define('TOUCH_TEAM_LINKS_GATE', 'touchTeamLinks');
        define('TOUCH_TEAM_MEMBERS_GATE', 'touchTeamMembers');
        define('TOUCH_TEAM_CHAT_GATE', 'touchTeamChat');
    }
}
