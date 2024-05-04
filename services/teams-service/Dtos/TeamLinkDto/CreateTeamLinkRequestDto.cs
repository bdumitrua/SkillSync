using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamLinkDto
{
    public class CreateTeamLinkRequestDto : BaseRequestDto
    {
        [Required]
        public string Name { get; set; } = string.Empty;

        [Required]
        public string Url { get; set; } = string.Empty;

        [Required]
        public int TeamId { get; set; }

        [Required]
        public bool IsPrivate { get; set; }
        public string? Text { get; set; }
        public string? IconType { get; set; }
    }
}
