using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TeamsService.Attributes;
using TeamsService.Data;
using TeamsService.Dtos.TeamDto;
using TeamsService.Dtos.TeamLinkDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    [Route("api/teams/links")]
    [ApiController]
    public class TeamLinksController : BaseController
    {
        private readonly ITeamLinkRepository _teamLinkRepository;

        public TeamLinksController(ITeamLinkRepository teamLinkRepository)
        {
            _teamLinkRepository = teamLinkRepository;
        }

        [HttpGet("{teamId}")]
        public async Task<ActionResult<List<TeamLink>>> GetByTeamId(int teamId)
        {
            bool isMember = await AuthorizedUserIsMember(teamId);

            List<TeamLink> teamLinks = await _teamLinkRepository.GetByTeamIdAsync(teamId, isMember);

            return Ok(teamLinks);
        }

        [HttpPost("{teamId}")]
        public async Task<ActionResult> Create(
            [BindTeam] Team team,
            CreateTeamLinkRequestDto requestDto
        )
        {
            await AuthorizedUserIsModerator(team.Id);

            TeamLink teamLinkModel = requestDto.TeamLinkFromCreateRequestDTO(team.Id);
            TeamLink newLink = await _teamLinkRepository.CreateAsync(teamLinkModel);

            return Ok(newLink);
        }

        [HttpPut("{teamLinkId}")]
        public async Task<ActionResult> Update(
            [BindTeamLink] TeamLink teamLink,
            UpdateTeamLinkRequestDto requestDto
        )
        {
            await AuthorizedUserIsModerator(teamLink.TeamId);

            await _teamLinkRepository.UpdateAsync(teamLink, requestDto);

            return Ok();
        }

        [HttpDelete("{teamLinkId}")]
        public async Task<ActionResult> Delete([BindTeamLink] TeamLink teamLink)
        {
            await AuthorizedUserIsModerator(teamLink.TeamId);

            await _teamLinkRepository.DeleteAsync(teamLink);

            return Ok();
        }
    }
}
