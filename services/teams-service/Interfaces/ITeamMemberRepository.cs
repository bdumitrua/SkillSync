using TeamsService.Dtos.Team;
using TeamsService.Models;

namespace TeamsService.Intefaces
{
    public interface ITeamMemberRepository
    {
        Task<List<TeamMember>> GetByTeamIdAsync(int teamId);
        Task<TeamMember> AddMemberAsync(TeamMember teamMember);
        Task<TeamMember?> RemoveMemberAsync(TeamMemberRequestDto teamMemberRequestDto);
    }
}
