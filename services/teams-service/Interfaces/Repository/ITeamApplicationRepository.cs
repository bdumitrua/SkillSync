using TeamsService.Dtos.TeamApplicationDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamApplicationRepository
    {
        // check moder rights
        Task<List<TeamApplication>> GetByTeamIdAsync(int teamId);

        // check moder rights
        Task<List<TeamApplication>> GetByVacancyIdAsync(int teamVacancyId);

        // check author or moder rights
        Task<TeamApplication?> GetByIdAsync(int teamApplicationId);

        // check unique
        // check if not member
        Task<TeamApplication?> CreateAsync(TeamApplication teamApplicationModel);

        // check moder rights
        Task<TeamApplication> UpdateAsync(
            TeamApplication teamApplication,
            UpdateTeamApplicationRequestDto updateTeamApplicationDto
        );

        // check moder
        Task<TeamApplication?> DeleteAsync(TeamApplication teamApplication);
    }
}
