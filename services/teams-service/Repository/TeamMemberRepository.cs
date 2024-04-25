using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.Team;
using TeamsService.Intefaces;
using TeamsService.Mappers;
using TeamsService.Models;

namespace TeamsService.Repository
{
    public class TeamMemberRepository : ITeamMemberRepository
    {
        private readonly ApplicationDBContext _context;

        public TeamMemberRepository(ApplicationDBContext context)
        {
            _context = context;
        }

        public async Task<TeamMember> AddMemberAsync(TeamMember teamMember)
        {
            await _context.TeamMembers.AddAsync(teamMember);
            await _context.SaveChangesAsync();

            return teamMember;
        }

        public async Task<List<TeamMember>> GetByTeamIdAsync(int teamId)
        {
            return await _context
                .TeamMembers.Where(teamMember => teamMember.TeamId == teamId)
                .ToListAsync();
        }

        public async Task<TeamMember?> RemoveMemberAsync(TeamMemberRequestDto teamMemberRequestDto)
        {
            TeamMember? teamMember = await _context.TeamMembers.FirstOrDefaultAsync(teamMember =>
                teamMember.UserId == teamMemberRequestDto.UserId
                && teamMember.TeamId == teamMemberRequestDto.TeamId
            );

            if (teamMember == null)
            {
                return null;
            }

            _context.TeamMembers.Remove(teamMember);
            await _context.SaveChangesAsync();

            return teamMember;
        }
    }
}
