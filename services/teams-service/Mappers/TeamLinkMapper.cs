using TeamsService.Dtos.TeamLinkDto;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class TeamLinkMapper
    {
        public static TeamLinkDto TeamLinkToDto(this TeamLink teamLink)
        {
            return new TeamLinkDto
            {
                Id = teamLink.Id,
                Name = teamLink.Name,
                Url = teamLink.Url,
                Text = teamLink.Text,
                IconType = teamLink.IconType,
                IsPrivate = teamLink.IsPrivate,
                CreatedAt = teamLink.CreatedAt,
            };
        }

        public static TeamLink TeamLinkFromCreateRequestDTO(
            this CreateTeamLinkRequestDto requestDto,
            int teamId
        )
        {
            return new TeamLink
            {
                Name = requestDto.Name,
                Url = requestDto.Url,
                IsPrivate = requestDto.IsPrivate,
                Text = requestDto.Text,
                IconType = requestDto.IconType,
                TeamId = teamId,
            };
        }
    }
}
