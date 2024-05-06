using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Models;

namespace TeamsService.Intefaces.Repository
{
    public interface ITeamMemberRepository
    {
        // add admin +
        Task<List<TeamMember>> GetByTeamIdAsync(int teamId);
        Task<TeamMember?> GetMemberByBothIds(int teamId, int userId);

        // check moder rights +
        // Check unique +
        Task<TeamMember?> AddMemberAsync(TeamMember teamMember);

        // check moder rights +
        // check if exists +
        // check if not admin +
        Task<bool?> RemoveMemberAsync(
            int teamId,
            RemoveTeamMemberRequestDto removeTeamMemberRequestDto
        );
    }
}
