using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamDto
{
    public class CreateTeamRequestDto : BaseRequestDto
    {
        [Required]
        public string Name { get; set; } = string.Empty;
        public string? Avatar { get; set; }
        public string? Description { get; set; }
        public string? Email { get; set; }
        public string? Site { get; set; }

        [Required]
        public int AdminId { get; set; }
    }
}
