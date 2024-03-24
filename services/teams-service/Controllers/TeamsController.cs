using Microsoft.AspNetCore.Mvc;
using TeamsService.Data;
using TeamsService.Dtos.Team;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class TeamsController : ControllerBase
    {
        private readonly ApplicationDBContext _context;

        public TeamsController(ApplicationDBContext context)
        {
            _context = context;
        }

        [HttpGet]
        public ActionResult<List<Team>> GetAll()
        {
            var teams = _context.Teams.ToList().Select(t => t.ToTeamDto());

            return Ok(teams);
        }

        [HttpGet("{id}")]
        public ActionResult<Team> GetById(int id)
        {
            var team = _context.Teams.Find(id);

            if (team == null)
            {
                return NotFound();
            }

            return Ok(team);
        }

        [HttpPost]
        public IActionResult Create([FromBody] CreateTeamRequestDto teamDto)
        {
            var teamModel = teamDto.ToTeamFromCreateDTO();

            _context.Teams.Add(teamModel);
            _context.SaveChanges();

            return CreatedAtAction(nameof(GetById), new { id = teamModel.Id }, teamModel);
        }
    }
}
