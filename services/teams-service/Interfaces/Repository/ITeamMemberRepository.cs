using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamMemberRepository
    {
        Task<List<TeamMemberDto>> GetByTeamIdAsync(int teamId);
        Task<TeamMemberDto> AddMemberAsync(TeamMember teamMember);
        Task<bool?> RemoveMemberAsync(RemoveTeamMemberRequestDto teamMemberRequestDto);
    }
}
