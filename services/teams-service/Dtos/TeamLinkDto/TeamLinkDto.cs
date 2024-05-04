namespace TeamsService.Dtos.TeamLinkDto
{
    public class TeamLinkDto
    {
        public int Id { get; set; }
        public string Name { get; set; } = string.Empty;
        public string Url { get; set; } = string.Empty;
        public string? Text { get; set; }
        public string? IconType { get; set; }
        public bool IsPrivate { get; set; }
        public DateTime CreatedAt { get; set; }
    }
}
