namespace TeamsService.Dtos.Team
{
    public class UpdateTeamRequestDto : BaseRequestDto
    {
        public string? Name { get; set; }
        public string? Avatar { get; set; }
        public string? Description { get; set; }
    }
}
