using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamLinkDto
{
    public class UpdateTeamLinkRequestDto : BaseRequestDto
    {
        [Required]
        [MaxLength(30, ErrorMessage = "Name can't exceed 30 characters.")]
        public string Name { get; set; } = string.Empty;

        [Required]
        [Url(ErrorMessage = "Invalid URL format.")]
        public string Url { get; set; } = string.Empty;

        [Required(ErrorMessage = "IsPrivate is required.")]
        public bool IsPrivate { get; set; }

        [MaxLength(30, ErrorMessage = "Text can't exceed 30 characters.")]
        public string? Text { get; set; }

        [MaxLength(30, ErrorMessage = "IconType can't exceed 30 characters.")]
        public string? IconType { get; set; }
    }
}
