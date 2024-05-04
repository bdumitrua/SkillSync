using TeamsService.Dtos.TeamApplicationDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamApplicationRepository
    {
        Task<List<TeamApplication>> GetByTeamIdAsync(int teamId);
        Task<TeamApplication?> GetByIdAsync(int id);
        Task<TeamApplication> CreateAsync(TeamApplication teamApplicationModel);
        Task<TeamApplication?> UpdateAsync(
            TeamApplication teamApplication,
            UpdateTeamApplicationRequestDto updateTeamApplicationDto
        );
        Task<TeamApplication?> DeleteAsync(TeamApplication teamApplication);
    }
}
