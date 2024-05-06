using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.TeamLinkDto;
using TeamsService.Intefaces.Repository;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Repository
{
    public class TeamLinkRepository : ITeamLinkRepository
    {
        private readonly ApplicationDBContext _context;

        public TeamLinkRepository(ApplicationDBContext context)
        {
            _context = context;
        }

        public async Task<TeamLink> CreateAsync(TeamLink teamLinkModel)
        {
            await _context.TeamLinks.AddAsync(teamLinkModel);
            await _context.SaveChangesAsync();

            return teamLinkModel;
        }

        public async Task<TeamLink?> DeleteAsync(TeamLink teamLink)
        {
            _context.TeamLinks.Remove(teamLink);
            await _context.SaveChangesAsync();

            return teamLink;
        }

        public async Task<TeamLink?> GetByIdAsync(int teamLinkId)
        {
            return await _context.TeamLinks.FirstOrDefaultAsync(link => link.Id == teamLinkId);
        }

        public async Task<List<TeamLink>> GetByTeamIdAsync(int teamId, bool isMember)
        {
            if (isMember)
                return await _context.TeamLinks.Where(link => link.TeamId == teamId).ToListAsync();

            return await _context
                .TeamLinks.Where(link => link.TeamId == teamId && link.IsPrivate == false)
                .ToListAsync();
        }

        public async Task<TeamLink?> UpdateAsync(
            TeamLink teamLink,
            UpdateTeamLinkRequestDto updateTeamLinkDto
        )
        {
            teamLink.UpdateModelFromDto(updateTeamLinkDto);

            await _context.SaveChangesAsync();

            return teamLink;
        }
    }
}
