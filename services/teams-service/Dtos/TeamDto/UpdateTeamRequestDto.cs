using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamDto
{
    public class UpdateTeamRequestDto : BaseRequestDto
    {
        [MaxLength(30, ErrorMessage = "Name can't exceed 30 characters.")]
        public string? Name { get; set; }

        [Url(ErrorMessage = "Invalid URL format for Avatar.")]
        public string? Avatar { get; set; }

        [MaxLength(200, ErrorMessage = "Description can't exceed 200 characters.")]
        public string? Description { get; set; }

        [EmailAddress(ErrorMessage = "Invalid Email format.")]
        public string? Email { get; set; }

        [Url(ErrorMessage = "Invalid URL format for Site.")]
        public string? Site { get; set; }

        [Range(1, int.MaxValue, ErrorMessage = "ChatId must be greater than 0.")]
        public int? ChatId { get; set; }
    }
}
