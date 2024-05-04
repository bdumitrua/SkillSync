using TeamsService.Dtos.TeamDto;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class TeamMapper
    {
        public static TeamDto TeamToDto(this Team teamModel)
        {
            return new TeamDto
            {
                Id = teamModel.Id,
                Name = teamModel.Name,
                Avatar = teamModel.Avatar,
                Description = teamModel.Description
            };
        }

        public static Team TeamFromCreateRequestDTO(this CreateTeamRequestDto TeamDto)
        {
            return new Team
            {
                Name = TeamDto.Name,
                Avatar = TeamDto.Avatar,
                Description = TeamDto.Description
            };
        }
    }
}
