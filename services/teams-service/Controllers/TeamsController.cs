using Microsoft.AspNetCore.Mvc;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class TeamsController : ControllerBase
    {
        // Для примера добавим статичный список команд
        private static List<Team> teams = new List<Team>
        {
            new Team { Id = 1, Name = "Team A" },
            new Team { Id = 2, Name = "Team AA" },
        };

        [HttpGet]
        public ActionResult<List<Team>> GetAll() => teams;

        [HttpGet("{id}")]
        public ActionResult<Team> Get(int id)
        {
            var team = teams.FirstOrDefault(t => t.Id == id);

            if (team == null)
            {
                return NotFound();
            }

            return team;
        }
    }
}
