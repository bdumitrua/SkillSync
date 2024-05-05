using TeamsService.Dtos.TeamLinkDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamLinkRepository
    {
        // filter private
        Task<List<TeamLink>> GetByTeamIdAsync(int teamId);

        // check private
        Task<TeamLink?> GetByIdAsync(int id);

        // check moder rights
        Task<TeamLink> CreateAsync(TeamLink teamLinkModel);

        // check moder rights
        Task<TeamLink?> UpdateAsync(TeamLink teamLink, UpdateTeamLinkRequestDto updateTeamLinkDto);

        // check moder rights
        Task<TeamLink?> DeleteAsync(TeamLink teamLink);
    }
}
