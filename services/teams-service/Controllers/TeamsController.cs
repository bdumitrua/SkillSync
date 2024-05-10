using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TeamsService.Attributes;
using TeamsService.Data;
using TeamsService.Dtos.TeamDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    [Route("api/teams")]
    [ApiController]
    public class TeamsController : BaseController
    {
        private readonly ITeamRepository _teamRepository;
        private readonly ITeamMemberRepository _teamMemberRepository;

        public TeamsController(
            ITeamRepository teamRepository,
            ITeamMemberRepository teamMemberRepository
        )
        {
            _teamRepository = teamRepository;
            _teamMemberRepository = teamMemberRepository;
        }

        [HttpGet]
        public async Task<ActionResult<List<TeamDto>>> GetAll()
        {
            var teams = await _teamRepository.GetAllAsync();
            var teamDto = teams.Select(t => t.TeamToDto());

            return Ok(teamDto);
        }

        [HttpGet("{teamId}")]
        public ActionResult<Team> GetById([BindTeam] Team team)
        {
            return Ok(team);
        }

        [HttpGet("user/{userId}")]
        public async Task<ActionResult<List<Team>>> GetByUserId(int userId)
        {
            List<TeamMember> userMemberships = await _teamMemberRepository.GetByUserIdAsync(userId);

            int[] uniqueTeamIds = userMemberships.Select(team => team.TeamId).Distinct().ToArray();
            List<Team> userTeams = await _teamRepository.GetByIdsAsync(uniqueTeamIds);

            return Ok(userTeams);
        }

        [HttpPost]
        public async Task<IActionResult> Create([FromBody] CreateTeamRequestDto teamDto)
        {
            int authorizedUserId = GetAuthorizedUserId();

            Team? teamModel = teamDto.TeamFromCreateRequestDTO(authorizedUserId);
            await _teamRepository.CreateAsync(teamModel);

            TeamMember teamMember = new TeamMember
            {
                IsModerator = true,
                UserId = authorizedUserId,
                TeamId = teamModel.Id,
            };

            await _teamMemberRepository.AddMemberAsync(teamMember);

            return Ok();
        }

        [HttpPut]
        [Route("{teamId}")]
        public async Task<ActionResult<TeamDto>> Update(
            [BindTeam] Team team,
            UpdateTeamRequestDto updateTeamDto
        )
        {
            if (updateTeamDto.IsEmpty())
                return BadRequest();

            if (team.AdminId != GetAuthorizedUserId())
                return Forbidden(
                    new { error = "You do not have permission to perform this action." }
                );

            Team? teamModel = await _teamRepository.UpdateAsync(team, updateTeamDto);

            if (teamModel == null)
                return NotFound();

            return Ok(teamModel.TeamToDto());
        }

        [HttpDelete]
        [Route("{teamId}")]
        public async Task<IActionResult> Delete([BindTeam] Team team)
        {
            if (team.AdminId != GetAuthorizedUserId())
                return Forbidden(
                    new { error = "You do not have permission to perform this action." }
                );

            await _teamRepository.DeleteAsync(team);

            return NoContent();
        }
    }
}
