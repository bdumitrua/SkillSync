using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.Team;
using TeamsService.Intefaces;
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

        [HttpGet("{id}")]
        public async Task<ActionResult<List<TeamMember>>> GetById(int id)
        {
            var teamMember = await _teamMemberRepository.GetByTeamIdAsync(id);

            return Ok(teamMember);
        }

        [HttpPost]
        public async Task<ActionResult<TeamMember>> Add(
            [FromBody] TeamMemberRequestDto createTeamMemberDto
        )
        {
            TeamMember teamMember = createTeamMemberDto.ToTeamMemberFromRequestDTO();
            await _teamMemberRepository.AddMemberAsync(teamMember);

            return Ok(teamMember);
        }

        [HttpDelete]
        public async Task<IActionResult> Remove(
            [FromBody] TeamMemberRequestDto teamMemberRequestDto
        )
        {
            TeamMember? teamMember = await _teamMemberRepository.RemoveMemberAsync(
                teamMemberRequestDto
            );

            if (teamMember == null)
            {
                return NotFound();
            }

            return NoContent();
        }
    }
}
