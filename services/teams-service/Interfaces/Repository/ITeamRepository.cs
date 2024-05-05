using TeamsService.Dtos.TeamDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamRepository
    {
        // DEV
        Task<List<Team>> GetAllAsync();

        // -
        Task<Team?> GetByIdAsync(int id);

        // -, mb unique
        Task<Team> CreateAsync(Team teamModel);

        // adminId check
        Task<Team?> UpdateAsync(Team team, UpdateTeamRequestDto updateTeamDto);

        // adminId check
        Task<Team?> DeleteAsync(Team team);
    }
}
