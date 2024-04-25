using TeamsService.Dtos.Team;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class TeamMemberMapper
    {
        public static TeamMemberDto ToTeamMemberDto(this TeamMember teamMember)
        {
            return new TeamMemberDto
            {
                Id = teamMember.Id,
                UserId = teamMember.UserId,
                TeamId = teamMember.TeamId
            };
        }

        public static TeamMember ToTeamMemberFromRequestDTO(
            this TeamMemberRequestDto teamMemberRequestDto
        )
        {
            return new TeamMember
            {
                UserId = teamMemberRequestDto.UserId,
                TeamId = teamMemberRequestDto.TeamId,
            };
        }
    }
}
