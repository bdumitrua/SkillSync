namespace TeamsService.Dtos.Team
{
    public class TeamMemberRequestDto : BaseRequestDto
    {
        public int UserId { get; set; }
        public int TeamId { get; set; }
    }
}
