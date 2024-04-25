namespace TeamsService.Dtos.Team
{
    public class CreateTeamRequestDto : BaseRequestDto
    {
        public string Name { get; set; } = string.Empty;
        public string Avatar { get; set; } = string.Empty;
        public string Description { get; set; } = string.Empty;
    }
}
