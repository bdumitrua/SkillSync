using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TeamsService.Attributes;
using TeamsService.Data;
using TeamsService.Dtos.TeamDto;
using TeamsService.Dtos.TeamVacancyDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    [Route("api/teams/vacancies")]
    [ApiController]
    public class TeamVacanciesController : BaseController
    {
        private readonly ITeamVacancyRepository _teamVacancyRepository;

        public TeamVacanciesController(ITeamVacancyRepository teamVacancyRepository)
        {
            _teamVacancyRepository = teamVacancyRepository;
        }

        [HttpGet("{teamId}")]
        public async Task<ActionResult<List<TeamVacancy>>> GetByTeamId(int teamId)
        {
            List<TeamVacancy> teamVacancies = await _teamVacancyRepository.GetByTeamIdAsync(teamId);

            return Ok(teamVacancies);
        }

        [HttpGet("show/{teamVacancyId}")]
        public ActionResult<TeamVacancy> GetById([BindTeamVacancy] TeamVacancy teamVacancy)
        {
            return Ok(teamVacancy);
        }

        [HttpPost]
        public async Task<ActionResult> Create([FromBody] CreateTeamVacancyRequestDto requestDto)
        {
            bool IsModerator = await AuthorizedUserIsModerator(
                requestDto.TeamId,
                GetAuthorizedUserId()
            );

            if (!IsModerator)
                return Forbidden(
                    new { error = "You do not have permission to perform this action." }
                );

            TeamVacancy teamVacancyModel = requestDto.TeamVacancyFromCreateRequestDTO();
            await _teamVacancyRepository.CreateAsync(teamVacancyModel);

            return Ok(teamVacancyModel);
        }

        [HttpPut("{teamVacancyId}")]
        public async Task<ActionResult> Update(
            [BindTeamVacancy] TeamVacancy teamVacancy,
            [FromBody] UpdateTeamVacancyRequestDto requestDto
        )
        {
            bool IsModerator = await AuthorizedUserIsModerator(
                teamVacancy.TeamId,
                GetAuthorizedUserId()
            );

            if (!IsModerator)
                return Forbidden(
                    new { error = "You do not have permission to perform this action." }
                );

            await _teamVacancyRepository.UpdateAsync(teamVacancy, requestDto);

            return Ok();
        }

        [HttpDelete("{teamVacancyId}")]
        public async Task<ActionResult> Delete([BindTeamVacancy] TeamVacancy teamVacancy)
        {
            bool IsModerator = await AuthorizedUserIsModerator(
                teamVacancy.TeamId,
                GetAuthorizedUserId()
            );

            if (!IsModerator)
                return Forbidden(
                    new { error = "You do not have permission to perform this action." }
                );

            await _teamVacancyRepository.DeleteAsync(teamVacancy);

            return Ok();
        }
    }
}
