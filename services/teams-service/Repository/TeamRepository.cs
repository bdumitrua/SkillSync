using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.TeamDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Repository
{
    public class TeamRepository : ITeamRepository
    {
        private readonly ApplicationDBContext _context;

        public TeamRepository(ApplicationDBContext context)
        {
            _context = context;
        }

        public async Task<Team> CreateAsync(Team teamModel)
        {
            await _context.Teams.AddAsync(teamModel);
            await _context.SaveChangesAsync();

            return teamModel;
        }

        public async Task<Team?> DeleteAsync(Team team)
        {
            _context.Teams.Remove(team);
            await _context.SaveChangesAsync();

            return team;
        }

        public Task<List<Team>> GetAllAsync()
        {
            return _context.Teams.ToListAsync();
        }

        public async Task<Team?> GetByIdAsync(int id)
        {
            return await _context.Teams.FirstOrDefaultAsync(team => team.Id == id);
        }

        public async Task<List<Team>> GetByIdsAsync(IEnumerable<int> teamIds)
        {
            return await _context.Teams.Where(team => teamIds.Contains(team.Id)).ToListAsync();
        }

        public async Task<Team?> UpdateAsync(Team team, UpdateTeamRequestDto updateTeamDto)
        {
            team.UpdateModelFromDto(updateTeamDto);

            await _context.SaveChangesAsync();

            return team;
        }
    }
}
