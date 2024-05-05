using System.Text.Json.Serialization;

namespace TeamsService.Models
{
    public class Team : BaseModel
    {
        public string Name { get; set; } = string.Empty;
        public string? Avatar { get; set; }
        public string? Description { get; set; }
        public string? Email { get; set; }
        public string? Site { get; set; }
        public int? ChatId { get; set; }
        public int AdminId { get; set; }

        public List<TeamScope> TeamScopes { get; set; } = new List<TeamScope>();
        public List<TeamMember> TeamMembers { get; set; } = new List<TeamMember>();

        [JsonIgnore]
        public List<TeamLink> TeamLinks { get; set; } = new List<TeamLink>();

        [JsonIgnore]
        public List<TeamVacancy> TeamVacancies { get; set; } = new List<TeamVacancy>();

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public DateTime UpdatedAt { get; set; } = DateTime.UtcNow;
    }
}
