using TeamsService.Dtos.TeamDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamRepository
    {
        // -
        Task<List<Team>> GetAllAsync();

        // -
        Task<Team?> GetByIdAsync(int id);

        // -
        Task<Team> CreateAsync(Team teamModel);

        // adminId check +
        Task<Team?> UpdateAsync(Team team, UpdateTeamRequestDto updateTeamDto);

        // adminId check +
        Task<Team?> DeleteAsync(Team team);
    }
}
