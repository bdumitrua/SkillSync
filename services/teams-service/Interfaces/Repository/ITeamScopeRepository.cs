using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamScopeRepository
    {
        // -
        Task<List<TeamScope>> GetByTeamIdAsync(int teamId);

        // check moder rights
        Task<TeamScope> CreateAsync(TeamScope teamScopeModel);

        // check moder rights
        Task<TeamScope?> DeleteAsync(TeamScope teamScope);
    }
}
