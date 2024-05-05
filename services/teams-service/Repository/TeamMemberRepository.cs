using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Intefaces.Repository;
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

        public async Task<TeamMemberDto> AddMemberAsync(TeamMember teamMember)
        {
            await _context.TeamMembers.AddAsync(teamMember);
            await _context.SaveChangesAsync();

            return teamMember.TeamMemberToDto();
        }

        public async Task<List<TeamMemberDto>> GetByTeamIdAsync(int teamId)
        {
            List<TeamMember> teamMembers = await _context
                .TeamMembers.Where(teamMember => teamMember.TeamId == teamId)
                .ToListAsync();

            List<TeamMemberDto> teamMemberDtos = teamMembers
                .Select(tm => tm.TeamMemberToDto())
                .ToList();

            return teamMemberDtos;
        }

        public async Task<bool?> RemoveMemberAsync(
            RemoveTeamMemberRequestDto removeTeamMemberRequestDto
        )
        {
            TeamMember? teamMember = await _context.TeamMembers.FirstOrDefaultAsync(teamMember =>
                teamMember.UserId == removeTeamMemberRequestDto.UserId
                && teamMember.TeamId == removeTeamMemberRequestDto.TeamId
            );

            if (teamMember == null)
            {
                return null;
            }

            _context.TeamMembers.Remove(teamMember);
            await _context.SaveChangesAsync();

            return true;
        }
    }
}
