using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TeamsService.Attributes;
using TeamsService.Data;
using TeamsService.Dtos.TeamDto;
using TeamsService.Dtos.TeamScopeDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    [Route("api/teams/scopes")]
    [ApiController]
    public class TeamScopesController : BaseController
    {
        private readonly ITeamScopeRepository _teamScopeRepository;

        public TeamScopesController(ITeamScopeRepository teamScopeRepository)
        {
            _teamScopeRepository = teamScopeRepository;
        }

        [HttpGet("{teamId}")]
        public async Task<ActionResult<List<TeamScope>>> GetByTeamId(int teamId)
        {
            List<TeamScope> teamScopes = await _teamScopeRepository.GetByTeamIdAsync(teamId);

            return Ok(teamScopes);
        }

        [HttpPost("{teamId}")]
        public async Task<ActionResult<TeamScope>> Create(
            [BindTeam] Team team,
            CreateTeamScopeRequestDto requestDto
        )
        {
            await AuthorizedUserIsModerator(team.Id);

            TeamScope teamScopeModel = requestDto.TeamScopeFromCreateRequestDTO(team.Id);
            await _teamScopeRepository.CreateAsync(teamScopeModel);

            return Ok(teamScopeModel);
        }

        [HttpDelete("{teamScopeId}")]
        public async Task<ActionResult> Delete([BindTeamScope] TeamScope teamScope)
        {
            await AuthorizedUserIsModerator(teamScope.TeamId);

            await _teamScopeRepository.DeleteAsync(teamScope);

            return Ok();
        }
    }
}
