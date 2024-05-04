using TeamsService.Dtos.TeamApplicationDto;
using TeamsService.Dtos.TeamDto;
using TeamsService.Dtos.TeamScopeDto;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class TeamScopeMapper
    {
        public static TeamScopeDto TeamScopeToDto(this TeamScope teamScope)
        {
            return new TeamScopeDto
            {
                Title = teamScope.Title,
                TeamId = teamScope.TeamId,
                CreatedAt = teamScope.CreatedAt,
            };
        }

        public static TeamScope TeamScopeFromCreateRequestDTO(
            this CreateTeamScopeRequestDto requestDto
        )
        {
            return new TeamScope { Title = requestDto.Title, TeamId = requestDto.TeamId, };
        }
    }
}
