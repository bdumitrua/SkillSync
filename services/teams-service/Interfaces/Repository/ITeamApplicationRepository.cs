using TeamsService.Dtos.TeamApplicationDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamApplicationRepository
    {
        // check moder rights
        Task<List<TeamApplication>> GetByTeamIdAsync(int teamId);

        // check moder rights
        Task<TeamApplication?> GetByIdAsync(int id);

        // check unique
        // check if member
        Task<TeamApplication> CreateAsync(TeamApplication teamApplicationModel);

        // check moder rights
        Task<TeamApplication?> UpdateAsync(
            TeamApplication teamApplication,
            UpdateTeamApplicationRequestDto updateTeamApplicationDto
        );

        // check author / moder
        Task<TeamApplication?> DeleteAsync(TeamApplication teamApplication);
    }
}
