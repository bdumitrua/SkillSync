<?php

namespace Tests\Feature\Team;

use App\Http\Resources\User\UserDataResource;
use App\Models\Team;
use App\Models\TeamMember;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $authorizedUser;
    protected $secondUser;
    protected $thirdUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizedUser = User::factory()->create();
        $this->secondUser = User::factory()->create();
        $this->thirdUser = User::factory()->create();
        $this->actingAs($this->authorizedUser, 'api');
    }

    protected function getTeamRequiredData(): array
    {
        return [
            'name' => 'Test Team',
        ];
    }

    protected function getTeamFullData(): array
    {
        return [
            'name' => 'Test Team',
            'avatar' => 'http://example.com/avatar.jpg',
            'description' => 'This is a test team',
            'email' => 'test@example.com',
            'site' => 'http://example.com',
        ];
    }

    protected function getTeamInvalidData(): array
    {
        return [
            'name' => 123,
            'avatar' => 123,
            'description' => true,
            'email' => false,
            'site' => 123,
        ];
    }

    public function testIndexRouteBasic(): void
    {
        Team::factory(3)->create();
        $response = $this->getJson(route("teams.index"));

        $response->assertJsonCount(3)
            // @see TeamResource
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'avatar',
                    'description',
                    'email',
                    'site',
                    'chatId',
                    'chatData',
                    'adminId',
                    'posts',
                    'tags',
                    'links',
                ],
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testIndexRouteEmpty(): void
    {
        $response = $this->getJson(route("teams.index"));

        $response->assertJsonCount(0)
            ->assertJsonStructure([
                '*' => [],
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testShowRouteBasic(): void
    {
        $team = (Team::factory(1)->create())->first();
        $response = $this->getJson(route("teams.show", ['team' => $team->id]));

        // @see TeamResource
        $response->assertJsonStructure(
            [
                'id',
                'name',
                'avatar',
                'description',
                'email',
                'site',
                'chatId',
                'chatData',
                'adminId',
                'posts',
                'tags',
                'links',
            ]
        )->assertStatus(Response::HTTP_OK);
    }

    // TODO TEST: PRIVATE LINKS
    public function testShowRouteEmpty(): void
    {
        $response = $this->getJson(route("teams.show", ['team' => 100]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUserRouteBasic(): void
    {
        Team::factory(3)->create(['admin_id' => $this->authorizedUser->id]);
        TeamMember::factory(1)->create(['user_id' => $this->secondUser->id]);

        $response = $this->getJson(route("teams.user", ['user' => $this->authorizedUser->id]));
        $response->assertJsonCount(3)
            // @see TeamDataResource
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'avatar',
                    'description',
                ],
            ])
            ->assertStatus(Response::HTTP_OK);

        $response = $this->getJson(route("teams.user", ['user' => $this->secondUser->id]));
        $response->assertJsonCount(1)
            // @see TeamDataResource
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'avatar',
                    'description',
                ],
            ])
            ->assertStatus(Response::HTTP_OK);

        $response = $this->getJson(route("teams.user", ['user' => $this->thirdUser->id]));
        $response->assertJsonCount(0)
            ->assertJsonStructure([
                '*' => [],
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testUserRouteEmpty(): void
    {
        $response = $this->getJson(route("teams.user", ['user' => $this->authorizedUser->id]));

        $response->assertJsonCount(0)
            ->assertJsonStructure([
                '*' => [],
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCreateRouteBasic(): void
    {
        $data = $this->getTeamRequiredData();

        $createResponse = $this->postJson(route("teams.create"), $data);
        $createResponse->assertStatus(Response::HTTP_OK);

        $showResponse = $this->getJson(route("teams.show", ['team' => 1]));
        // @see TeamResource
        $showResponse->assertExactJson([
            'id' => 1,
            'name' => $data['name'],
            'avatar' => null,
            'description' => null,
            'email' => null,
            'site' => null,
            'chatId' => null,
            'chatData' => [],
            'adminId' => $this->authorizedUser->id,
            'posts' => [],
            'tags' => [],
            'links' => [],
        ])->assertStatus(Response::HTTP_OK);

        $userResponse = $this->getJson(route("teams.user", ['user' => $this->authorizedUser->id]));
        $userResponse->assertJsonCount(1)
            ->assertExactJson([
                [
                    'name' => $data['name'],
                    'avatar' => null,
                    'description' => null,
                ]
            ])
            ->assertStatus(Response::HTTP_OK);

        $membersResponse = $this->getJson(route('teams.members.team', ['team' => 1]));

        $membersResponse->assertJsonCount(1)
            ->assertExactJson([
                [
                    'teamId' => 1,
                    'userId' => $this->authorizedUser->id,
                    'userData' => (new UserDataResource($this->authorizedUser))->resolve(),
                    'isModerator' => 1,
                    'about' => null,
                ]
            ]);
    }

    public function testCreateRouteInvalidData(): void
    {
        $data = $this->getTeamInvalidData();

        $createResponse = $this->postJson(route("teams.create"), $data);
        $createResponse->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreateRouteDuplicateName(): void
    {
        $team = (Team::factory(1)->create())->first();
        $data = ['name' => $team->name];

        $createResponse = $this->postJson(route("teams.create"), $data);
        $createResponse->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
