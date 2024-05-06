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
        // TODO
        // REMOVE _context
        private readonly ApplicationDBContext _context;
        private readonly ITeamScopeRepository _teamScopeRepository;

        public TeamScopesController(
            ApplicationDBContext context,
            ITeamScopeRepository teamScopeRepository
        )
        {
            _context = context;
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
            // TODO
            // throw ex
            bool IsModerator = await AuthorizedUserIsModerator(team.Id, GetAuthorizedUserId());

            if (!IsModerator)
                return Forbidden(
                    new { error = "You do not have permission to perform this action." }
                );

            TeamScope teamScopeModel = requestDto.TeamScopeFromCreateRequestDTO(team.Id);
            await _teamScopeRepository.CreateAsync(teamScopeModel);

            return Ok(teamScopeModel);
        }

        [HttpDelete("{teamScopeId}")]
        public async Task<ActionResult> Delete([BindTeamScope] TeamScope teamScope)
        {
            bool IsModerator = await AuthorizedUserIsModerator(
                teamScope.TeamId,
                GetAuthorizedUserId()
            );

            if (!IsModerator)
                return Forbidden(
                    new { error = "You do not have permission to perform this action." }
                );

            await _teamScopeRepository.DeleteAsync(teamScope);

            return Ok();
        }
    }
}
