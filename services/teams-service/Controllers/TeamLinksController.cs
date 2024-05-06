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
    [Route("api/teams/links")]
    [ApiController]
    public class TeamLinksController : BaseController
    {
        private readonly ITeamLinkRepository _teamLinkRepository;

        public TeamLinksController(ITeamLinkRepository teamLinkRepository)
        {
            _teamLinkRepository = teamLinkRepository;
        }
    }
}
