using TeamsService.Dtos.TeamDto;
using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class TeamMemberMapper
    {
        public static TeamMemberDto TeamMemberToDto(this TeamMember teamMember)
        {
            return new TeamMemberDto { UserId = teamMember.UserId, TeamId = teamMember.TeamId };
        }

        public static TeamMember ToTeamMemberFromRequestDTO(
            this CreateTeamMemberRequestDto teamMemberRequestDto
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
