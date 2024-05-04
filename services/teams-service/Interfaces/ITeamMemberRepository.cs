using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Models;

namespace TeamsService.Intefaces
{
    public interface ITeamMemberRepository
    {
        Task<List<TeamMember>> GetByTeamIdAsync(int teamId);
        Task<TeamMember> AddMemberAsync(TeamMember teamMember);
        Task<TeamMember?> RemoveMemberAsync(RemoveTeamMemberRequestDto teamMemberRequestDto);
    }
}
