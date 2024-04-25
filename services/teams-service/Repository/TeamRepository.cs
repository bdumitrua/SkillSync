using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.Team;
using TeamsService.Intefaces;
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

        public async Task<Team?> DeleteAsync(int id)
        {
            var teamModel = await _context.Teams.FirstOrDefaultAsync(team => team.Id == id);

            if (teamModel == null)
            {
                return null;
            }

            _context.Teams.Remove(teamModel);
            await _context.SaveChangesAsync();

            return teamModel;
        }

        public Task<List<Team>> GetAllAsync()
        {
            return _context.Teams.ToListAsync();
        }

        public async Task<Team?> GetByIdAsync(int id)
        {
            return await _context.Teams.FirstOrDefaultAsync(team => team.Id == id);
        }

        public async Task<Team?> UpdateAsync(int id, UpdateTeamRequestDto updateTeamDto)
        {
            var teamModel = await GetByIdAsync(id);

            if (teamModel == null)
            {
                return null;
            }

            teamModel.UpdateModelFromDto(updateTeamDto);

            await _context.SaveChangesAsync();

            return teamModel;
        }
    }
}
