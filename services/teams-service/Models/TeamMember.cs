namespace TeamsService.Models
{
    public class TeamMember : BaseModel
    {
        public int UserId { get; set; }
        public int TeamId { get; set; }
        public Team? Team { get; set; }
    }
}
