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

        public TeamsController(ITeamRepository teamRepository)
        {
            _teamRepository = teamRepository;
        }

        [HttpGet]
        [Authorize]
        public async Task<IActionResult> GetAll()
        {
            var teams = await _teamRepository.GetAllAsync();
            var teamDto = teams.Select(t => t.TeamToDto());

            return Ok(teamDto);
        }

        [HttpGet("{teamId}")]
        [Authorize]
        public ActionResult<Team> GetById([BindTeam] Team team)
        {
            return Ok(team);
        }

        [HttpPost]
        [Authorize]
        public async Task<IActionResult> Create([FromBody] CreateTeamRequestDto teamDto)
        {
            int authorizedUserId = GetAuthorizedUserId();

            Team? teamModel = teamDto.TeamFromCreateRequestDTO(authorizedUserId);
            await _teamRepository.CreateAsync(teamModel);

            return Ok();
        }

        [HttpPut]
        [Route("{teamId}")]
        [Authorize]
        public async Task<IActionResult> Update(
            [BindTeam] Team team,
            UpdateTeamRequestDto updateTeamDto
        )
        {
            if (updateTeamDto.IsEmpty())
            {
                return BadRequest();
            }

            Team? teamModel = await _teamRepository.UpdateAsync(team, updateTeamDto);

            if (teamModel == null)
            {
                return NotFound();
            }

            return Ok(teamModel.TeamToDto());
        }

        [HttpDelete]
        [Route("{teamId}")]
        [Authorize]
        public async Task<IActionResult> Delete([BindTeam] Team team)
        {
            await _teamRepository.DeleteAsync(team);

            return NoContent();
        }
    }
}
