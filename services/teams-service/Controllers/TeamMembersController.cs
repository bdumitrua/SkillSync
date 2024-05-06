using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TeamsService.Attributes;
using TeamsService.Data;
using TeamsService.Dtos.TeamDto;
using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    [Route("api/teams/members")]
    [ApiController]
    public class TeamMembersController : BaseController
    {
        private readonly ITeamMemberRepository _teamMemberRepository;

        public TeamMembersController(ITeamMemberRepository teamMemberRepository)
        {
            _teamMemberRepository = teamMemberRepository;
        }

        [HttpGet("{teamId}")]
        public async Task<ActionResult<List<TeamMember>>> GetById([BindTeam] Team team)
        {
            var teamMember = await _teamMemberRepository.GetByTeamIdAsync(team.Id);

            return Ok(teamMember);
        }

        [HttpPost("{teamId}")]
        public async Task<ActionResult> Add(
            int teamId,
            [FromBody] CreateTeamMemberRequestDto createTeamMemberDto
        )
        {
            await AuthorizedUserIsModerator(teamId);

            TeamMember teamMember = createTeamMemberDto.ToTeamMemberFromRequestDTO(teamId);
            TeamMember? newTeamMember = await _teamMemberRepository.AddMemberAsync(teamMember);

            if (newTeamMember == null)
                return BadRequest(new { error = "This member already exists" });

            return Ok(teamMember);
        }

        [HttpDelete("{teamId}")]
        public async Task<IActionResult> Remove(
            [BindTeam] Team team,
            [FromBody] RemoveTeamMemberRequestDto removeTeamMemberRequestDto
        )
        {
            if (removeTeamMemberRequestDto.UserId == team.AdminId)
            {
                return Forbidden(
                    new { error = "You do not have permission to perform this action." }
                );
            }

            await AuthorizedUserIsModerator(team.Id);

            bool? deletingStatus = await _teamMemberRepository.RemoveMemberAsync(
                team.Id,
                removeTeamMemberRequestDto
            );

            if (deletingStatus == null)
                return NotFound();

            return NoContent();
        }
    }
}
