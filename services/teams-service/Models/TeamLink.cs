namespace TeamsService.Models
{
    public class TeamLink : BaseModel
    {
        public string Name { get; set; }
        public string Url { get; set; }
        public string? Text { get; set; }
        public string? IconType { get; set; }
        public bool IsPrivate { get; set; } = false;
        public int TeamId { get; set; }
        public Team Team { get; set; }

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
    }
}
