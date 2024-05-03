namespace TeamsService.Models
{
    public class TeamMember : BaseModel
    {
        public int UserId { get; set; }
        public int TeamId { get; set; }
        public bool IsModerator { get; set; } = false;
        public string? About { get; set; }
        public Team? Team { get; set; }

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public DateTime UpdatedAt { get; set; } = DateTime.UtcNow;
    }
}
