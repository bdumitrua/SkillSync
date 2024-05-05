using TeamsService.Dtos.TeamVacancyDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamVacancyRepository
    {
        // -
        Task<List<TeamVacancy>> GetByTeamIdAsync(int teamId);

        // -
        Task<TeamScope?> GetByIdAsync(int id);

        // check moder rights
        Task<TeamScope> CreateAsync(TeamScope teamScopeModel);

        // check moder rights
        Task<TeamScope> UpdateAsync(
            TeamScope teamScope,
            UpdateTeamVacancyRequestDto updateRequestDto
        );

        // check moder rights
        Task<TeamScope?> DeleteAsync(TeamScope teamScope);
    }
}
