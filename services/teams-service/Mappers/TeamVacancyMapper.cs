using TeamsService.Dtos.TeamVacancyDto;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class TeamVacancyMapper
    {
        public static TeamVacancyDto TeamVacancyToDto(this TeamVacancy teamVacancy)
        {
            return new TeamVacancyDto
            {
                TeamId = teamVacancy.TeamId,
                Title = teamVacancy.Title,
                Description = teamVacancy.Description,
            };
        }

        public static TeamVacancy TeamVacancyFromCreateRequestDTO(
            this CreateTeamVacancyRequestDto requestDto
        )
        {
            return new TeamVacancy
            {
                Title = requestDto.Title,
                Description = requestDto.Description,
                TeamId = requestDto.TeamId,
            };
        }
    }
}
