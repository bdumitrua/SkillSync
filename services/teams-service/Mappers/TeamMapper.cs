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
                Description = teamModel.Description,
                Email = teamModel.Email,
                Site = teamModel.Site,
                ChatId = teamModel.ChatId,
                AdminId = teamModel.AdminId,
                CreatedAt = teamModel.CreatedAt,
                UpdatedAt = teamModel.UpdatedAt,
            };
        }

        public static Team TeamFromCreateRequestDTO(this CreateTeamRequestDto TeamDto, int userId)
        {
            return new Team
            {
                AdminId = userId,
                Name = TeamDto.Name,
                Avatar = TeamDto.Avatar,
                Description = TeamDto.Description,
                Email = TeamDto.Email,
                Site = TeamDto.Site,
            };
        }
    }
}
