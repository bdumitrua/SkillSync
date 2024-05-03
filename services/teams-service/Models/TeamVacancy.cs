namespace TeamsService.Models
{
    public class TeamVacancy : BaseModel
    {
        public string Title { get; set; }
        public string Description { get; set; }
        public int TeamId { get; set; }
        public Team Team { get; set; }

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public DateTime UpdatedAt { get; set; } = DateTime.UtcNow;
    }
}
