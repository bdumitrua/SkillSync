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
    public class TeamMembersController : ControllerBase
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

        [HttpPost]
        public async Task<ActionResult<TeamMember>> Add(
            [FromBody] CreateTeamMemberRequestDto createTeamMemberDto
        )
        {
            TeamMember teamMember = createTeamMemberDto.ToTeamMemberFromRequestDTO();
            await _teamMemberRepository.AddMemberAsync(teamMember);

            return Ok(teamMember);
        }

        [HttpDelete]
        public async Task<IActionResult> Remove(
            [FromBody] RemoveTeamMemberRequestDto removeTeamMemberRequestDto
        )
        {
            TeamMember? teamMember = await _teamMemberRepository.RemoveMemberAsync(
                removeTeamMemberRequestDto
            );

            if (teamMember == null)
            {
                return NotFound();
            }

            return NoContent();
        }
    }
}
