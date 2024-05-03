using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.Team;
using TeamsService.Intefaces;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    [Route("api/teams")]
    [ApiController]
    public class TeamsController : ControllerBase
    {
        private readonly ApplicationDBContext _context;
        private readonly ITeamRepository _teamRepository;

        public TeamsController(ApplicationDBContext context, ITeamRepository teamRepository)
        {
            _teamRepository = teamRepository;
            _context = context;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll()
        {
            var teams = await _teamRepository.GetAllAsync();
            var teamDto = teams.Select(t => t.TeamToDto());

            return Ok(teamDto);
        }

        [HttpGet("{id}")]
        public async Task<ActionResult<Team>> GetById(int id)
        {
            var team = await _teamRepository.GetByIdAsync(id);

            if (team == null)
            {
                return NotFound();
            }

            return Ok(team);
        }

        [HttpPost]
        public async Task<IActionResult> Create([FromBody] CreateTeamRequestDto teamDto)
        {
            Team? teamModel = teamDto.TeamFromCreateRequestDTO();
            await _teamRepository.CreateAsync(teamModel);

            return CreatedAtAction(nameof(GetById), new { id = teamModel.Id }, teamModel);
        }

        [HttpPut]
        [Route("{id}")]
        public async Task<IActionResult> Update(
            [FromRoute] int id,
            UpdateTeamRequestDto updateTeamDto
        )
        {
            if (updateTeamDto.IsEmpty())
            {
                return BadRequest();
            }

            Team? teamModel = await _teamRepository.UpdateAsync(id, updateTeamDto);

            if (teamModel == null)
            {
                return NotFound();
            }

            return Ok(teamModel.TeamToDto());
        }

        [HttpDelete]
        [Route("{id}")]
        public async Task<IActionResult> Delete([FromRoute] int id)
        {
            Team? teamModel = await _teamRepository.DeleteAsync(id);

            if (teamModel == null)
            {
                return NotFound();
            }

            return NoContent();
        }
    }
}
