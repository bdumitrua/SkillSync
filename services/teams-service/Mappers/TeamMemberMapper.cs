using TeamsService.Dtos.TeamDto;
using TeamsService.Dtos.TeamMemberDto;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class TeamMemberMapper
    {
        public static TeamMemberDto TeamMemberToDto(this TeamMember teamMember)
        {
            return new TeamMemberDto
            {
                UserId = teamMember.UserId,
                TeamId = teamMember.TeamId,
                About = teamMember.About,
                IsModerator = teamMember.IsModerator,
                CreatedAt = teamMember.CreatedAt,
                UpdatedAt = teamMember.UpdatedAt,
            };
        }

        public static TeamMember ToTeamMemberFromRequestDTO(
            this CreateTeamMemberRequestDto teamMemberRequestDto,
            int teamId
        )
        {
            return new TeamMember
            {
                IsModerator = teamMemberRequestDto.IsModerator,
                UserId = teamMemberRequestDto.UserId,
                TeamId = teamId,
            };
        }
    }
}
