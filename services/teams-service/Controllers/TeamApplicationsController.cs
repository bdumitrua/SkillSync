using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TeamsService.Attributes;
using TeamsService.Data;
using TeamsService.Dtos.TeamApplicationDto;
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
        private readonly ITeamApplicationRepository _teamApplicationRepository;

        public TeamApplicationsController(ITeamApplicationRepository teamApplicationRepository)
        {
            _teamApplicationRepository = teamApplicationRepository;
        }

        [HttpGet("{teamId}")]
        public async Task<ActionResult<List<TeamApplication>>> GetByTeamId([BindTeam] Team team)
        {
            await AuthorizedUserIsModerator(team.Id, GetAuthorizedUserId());

            List<TeamApplication> vacancyApplications =
                await _teamApplicationRepository.GetByTeamIdAsync(team.Id);

            return Ok(vacancyApplications);
        }

        [HttpGet("show/{teamApplicationId}")]
        public async Task<ActionResult<TeamApplication>> GetById(
            [BindTeamApplication] TeamApplication teamApplication
        )
        {
            int authorizedUserId = GetAuthorizedUserId();
            // If it's not author
            if (teamApplication.UserId != authorizedUserId)
                // check if it's an moderator
                await AuthorizedUserIsModerator(teamApplication.TeamId, authorizedUserId);

            return Ok(teamApplication);
        }

        [HttpGet("vacancy/{teamVacancyId}")]
        public async Task<ActionResult<List<TeamApplication>>> GetByVacancyId(
            [BindTeamVacancy] TeamVacancy teamVacancy
        )
        {
            await AuthorizedUserIsModerator(teamVacancy.TeamId, GetAuthorizedUserId());

            List<TeamApplication> teamApplications =
                await _teamApplicationRepository.GetByVacancyIdAsync(teamVacancy.Id);

            return Ok(teamApplications);
        }

        [HttpPost("{teamVacancyId}")]
        public async Task<ActionResult<TeamApplication>> Create(
            [BindTeamVacancy] TeamVacancy teamVacancy,
            [FromBody] CreateTeamApplicationRequestDto requestDto
        )
        {
            int authorizedUserId = GetAuthorizedUserId();
            bool isMember = await AuthorizedUserIsMember(teamVacancy.TeamId, authorizedUserId);
            if (isMember)
            {
                return BadRequest(
                    new
                    {
                        error = "You can't apply to a team vacancy if you are member of this team."
                    }
                );
            }

            TeamApplication teamApplicationModel = requestDto.TeamApplicationFromCreateRequestDTO(
                authorizedUserId
            );

            TeamApplication? newApplication = await _teamApplicationRepository.CreateAsync(
                teamApplicationModel
            );

            if (newApplication == null)
            {
                return BadRequest(new { error = "You already applied to this vacancy." });
            }

            return Ok(newApplication);
        }

        [HttpPatch("{teamApplicationId}")]
        public async Task<ActionResult> Update(
            [BindTeamApplication] TeamApplication teamApplication,
            [FromBody] UpdateTeamApplicationRequestDto requestDto
        )
        {
            await AuthorizedUserIsModerator(teamApplication.TeamId, GetAuthorizedUserId());

            TeamApplication updatedTeamApplication = await _teamApplicationRepository.UpdateAsync(
                teamApplication,
                requestDto
            );

            return Ok(updatedTeamApplication);
        }

        [HttpDelete("{teamApplicationId}")]
        public async Task<ActionResult> Delete(
            [BindTeamApplication] TeamApplication teamApplication
        )
        {
            await AuthorizedUserIsModerator(teamApplication.TeamId, GetAuthorizedUserId());

            await _teamApplicationRepository.DeleteAsync(teamApplication);

            return Ok();
        }
    }
}
