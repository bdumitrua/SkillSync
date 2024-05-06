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
    [Route("api/teams/applications")]
    [ApiController]
    public class TeamApplicationsController : BaseController
    {
        private readonly ApplicationDBContext _context;
        private readonly ITeamApplicationRepository _teamApplicationRepository;

        public TeamApplicationsController(
            ApplicationDBContext context,
            ITeamApplicationRepository teamApplicationRepository
        )
        {
            _context = context;
            _teamApplicationRepository = teamApplicationRepository;
        }
    }
}
