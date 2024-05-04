using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamScopeRepository
    {
        Task<List<TeamScope>> GetByTeamIdAsync(int teamId);
        Task<TeamScope> CreateAsync(TeamScope teamScopeModel);
        Task<TeamScope?> DeleteAsync(TeamScope teamScope);
    }
}
