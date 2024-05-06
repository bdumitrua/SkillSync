using TeamsService.Dtos.TeamVacancyDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamVacancyRepository
    {
        // -
        Task<List<TeamVacancy>> GetByTeamIdAsync(int teamId);

        // -
        Task<TeamVacancy?> GetByIdAsync(int teamVacancyId);

        // check moder rights +
        Task<TeamVacancy> CreateAsync(TeamVacancy teamVacancyModel);

        // check moder rights +
        Task<TeamVacancy> UpdateAsync(
            TeamVacancy teamVacancy,
            UpdateTeamVacancyRequestDto updateRequestDto
        );

        // check moder rights
        Task<TeamVacancy?> DeleteAsync(TeamVacancy teamVacancy);
    }
}
