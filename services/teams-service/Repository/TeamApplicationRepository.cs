using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.TeamApplicationDto;
using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Dtos.TeamVacancyDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Repository
{
    public class TeamApplicationRepository : ITeamApplicationRepository
    {
        private readonly ApplicationDBContext _context;

        public TeamApplicationRepository(ApplicationDBContext context)
        {
            _context = context;
        }

        public async Task<TeamApplication?> CreateAsync(TeamApplication teamApplicationModel)
        {
            TeamApplication? existingApplication =
                await _context.TeamApplications.FirstOrDefaultAsync(application =>
                    application.UserId == teamApplicationModel.UserId
                    && application.VacancyId == teamApplicationModel.VacancyId
                );

            if (existingApplication != null)
                return null;

            await _context.TeamApplications.AddAsync(teamApplicationModel);
            await _context.SaveChangesAsync();

            return teamApplicationModel;
        }

        public async Task<TeamApplication?> DeleteAsync(TeamApplication teamApplication)
        {
            _context.TeamApplications.Remove(teamApplication);
            await _context.SaveChangesAsync();

            return teamApplication;
        }

        public async Task<TeamApplication?> GetByIdAsync(int teamApplicationId)
        {
            return await _context.TeamApplications.FirstOrDefaultAsync(application =>
                application.Id == teamApplicationId
            );
        }

        public async Task<List<TeamApplication>> GetByTeamIdAsync(int teamId)
        {
            return await _context
                .TeamApplications.Where(application => application.TeamId == teamId)
                .ToListAsync();
        }

        public async Task<List<TeamApplication>> GetByVacancyIdAsync(int teamVacancyId)
        {
            return await _context
                .TeamApplications.Where(application => application.VacancyId == teamVacancyId)
                .ToListAsync();
        }

        public async Task<TeamApplication> UpdateAsync(
            TeamApplication teamApplication,
            UpdateTeamApplicationRequestDto updateTeamApplicationDto
        )
        {
            teamApplication.UpdateModelFromDto(updateTeamApplicationDto);
            await _context.SaveChangesAsync();

            return teamApplication;
        }
    }
}
