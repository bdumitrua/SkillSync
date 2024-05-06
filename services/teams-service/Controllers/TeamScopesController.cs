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
    [Route("api/teams/scopes")]
    [ApiController]
    public class TeamScopesController : BaseController
    {
        private readonly ApplicationDBContext _context;
        private readonly ITeamScopeRepository _teamScopeRepository;

        public TeamScopesController(
            ApplicationDBContext context,
            ITeamScopeRepository teamScopeRepository
        )
        {
            _context = context;
            _teamScopeRepository = teamScopeRepository;
        }
    }
}
