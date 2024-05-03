namespace TeamsService.Dtos.Team
{
    public class TeamDto
    {
        public int Id { get; set; }
        public string Name { get; set; } = string.Empty;
        public string? Avatar { get; set; }
        public string? Description { get; set; }
        public string? Email { get; set; }
        public string? Site { get; set; }
        public int? ChatId { get; set; }
        public int AdminId { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
