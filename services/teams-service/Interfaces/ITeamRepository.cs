using TeamsService.Dtos.TeamDto;
using TeamsService.Models;

namespace TeamsService.Intefaces
{
    public interface ITeamRepository
    {
        Task<List<Team>> GetAllAsync();
        Task<Team?> GetByIdAsync(int id);
        Task<Team> CreateAsync(Team teamModel);
        Task<Team?> UpdateAsync(Team team, UpdateTeamRequestDto updateTeamDto);
        Task<Team?> DeleteAsync(Team team);
    }
}
