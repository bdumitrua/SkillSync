using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamVacancyRepository
    {
        Task<List<TeamVacancy>> GetByTeamIdAsync(int teamId);
        Task<TeamScope?> GetByIdAsync(int id);
        Task<TeamScope> CreateAsync(TeamScope teamScopeModel);
        Task<TeamScope?> DeleteAsync(TeamScope teamScope);
    }
}
