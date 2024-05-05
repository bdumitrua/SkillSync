using System.Text.Json.Serialization;

namespace TeamsService.Models
{
    public class TeamLink : BaseModel
    {
        public string Name { get; set; } = string.Empty;
        public string Url { get; set; } = string.Empty;
        public string? Text { get; set; }
        public string? IconType { get; set; }
        public bool IsPrivate { get; set; } = false;
        public int TeamId { get; set; }

        [JsonIgnore]
        public Team? Team { get; set; }

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
    }
}
