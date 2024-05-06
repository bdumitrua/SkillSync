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
    public class TeamScopeRepository : ITeamScopeRepository
    {
        private readonly ApplicationDBContext _context;

        public TeamScopeRepository(ApplicationDBContext context)
        {
            _context = context;
        }

        public async Task<TeamScope> CreateAsync(TeamScope teamScopeModel)
        {
            await _context.TeamScopes.AddAsync(teamScopeModel);
            await _context.SaveChangesAsync();

            return teamScopeModel;
        }

        public async Task<TeamScope?> DeleteAsync(TeamScope teamScope)
        {
            _context.TeamScopes.Remove(teamScope);
            await _context.SaveChangesAsync();

            return teamScope;
        }

        public async Task<List<TeamScope>> GetByTeamIdAsync(int teamId)
        {
            List<TeamScope> teamScopes = await _context
                .TeamScopes.Where(scope => scope.TeamId == teamId)
                .ToListAsync();

            return teamScopes;
        }
    }
}
