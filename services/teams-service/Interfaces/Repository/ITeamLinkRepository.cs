using TeamsService.Dtos.TeamLinkDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamLinkRepository
    {
        Task<List<TeamLink>> GetByTeamIdAsync(int teamId);
        Task<TeamLink?> GetByIdAsync(int id);
        Task<TeamLink> CreateAsync(TeamLink teamLinkModel);
        Task<TeamLink?> UpdateAsync(TeamLink teamLink, UpdateTeamLinkRequestDto updateTeamLinkDto);
        Task<TeamLink?> DeleteAsync(TeamLink teamLink);
    }
}
