using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamMemberRepository
    {
        // add admin
        Task<List<TeamMemberDto>> GetByTeamIdAsync(int teamId);

        // check moder rights
        // Check unique
        // Check not admin
        Task<TeamMemberDto> AddMemberAsync(TeamMember teamMember);

        // check moder rights
        // Check if exists
        Task<bool?> RemoveMemberAsync(RemoveTeamMemberRequestDto teamMemberRequestDto);
    }
}
