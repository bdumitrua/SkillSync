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
    [Route("api/teams/vacancies")]
    [ApiController]
    public class TeamVacanciesController : ControllerBase
    {
        private readonly ApplicationDBContext _context;
        private readonly ITeamVacancyRepository _teamVacancyRepository;

        public TeamVacanciesController(
            ApplicationDBContext context,
            ITeamVacancyRepository teamVacancyRepository
        )
        {
            _context = context;
            _teamVacancyRepository = teamVacancyRepository;
        }
    }
}
