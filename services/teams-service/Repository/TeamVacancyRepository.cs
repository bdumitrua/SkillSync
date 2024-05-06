using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Dtos.TeamVacancyDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Repository
{
    public class TeamVacancyRepository : ITeamVacancyRepository
    {
        private readonly ApplicationDBContext _context;

        public TeamVacancyRepository(ApplicationDBContext context)
        {
            _context = context;
        }

        public async Task<TeamVacancy> CreateAsync(TeamVacancy teamVacancyModel)
        {
            await _context.TeamVacancies.AddAsync(teamVacancyModel);
            await _context.SaveChangesAsync();

            return teamVacancyModel;
        }

        public async Task<TeamVacancy?> DeleteAsync(TeamVacancy teamVacancy)
        {
            _context.Remove(teamVacancy);
            await _context.SaveChangesAsync();

            return teamVacancy;
        }

        public async Task<TeamVacancy?> GetByIdAsync(int teamVacancyId)
        {
            return await _context.TeamVacancies.FirstOrDefaultAsync(teamVacancy =>
                teamVacancy.Id == teamVacancyId
            );
        }

        public async Task<List<TeamVacancy>> GetByTeamIdAsync(int teamId)
        {
            return await _context
                .TeamVacancies.Where(teamVacancy => teamVacancy.TeamId == teamId)
                .ToListAsync();
        }

        public async Task<TeamVacancy> UpdateAsync(
            TeamVacancy teamVacancy,
            UpdateTeamVacancyRequestDto updateRequestDto
        )
        {
            teamVacancy.UpdateModelFromDto(updateRequestDto);

            await _context.SaveChangesAsync();

            return teamVacancy;
        }
    }
}
