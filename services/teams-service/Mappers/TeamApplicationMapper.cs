using TeamsService.Dtos.TeamApplicationDto;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class TeamApplicationMapper
    {
        public static TeamApplicationDto TeamApplicationToDto(this TeamApplication teamApplication)
        {
            return new TeamApplicationDto
            {
                Id = teamApplication.Id,
                Status = teamApplication.Status,
                Text = teamApplication.Text,
                UserId = teamApplication.UserId,
                VacancyId = teamApplication.VacancyId,
                TeamId = teamApplication.TeamId,
            };
        }

        public static TeamApplication TeamApplicationFromCreateRequestDTO(
            this CreateTeamApplicationRequestDto requestDto,
            int userId
        )
        {
            return new TeamApplication
            {
                Text = requestDto.Text,
                VacancyId = requestDto.VacancyId,
                TeamId = requestDto.TeamId,
                UserId = userId,
            };
        }
    }
}
