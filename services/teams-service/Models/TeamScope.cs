namespace TeamsService.Models
{
    public class TeamScope : BaseModel
    {
        public string Title { get; set; }
        public int TeamId { get; set; }
        public Team Team { get; set; }

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
    }
}
