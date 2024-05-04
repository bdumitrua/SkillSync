using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamApplicationDto
{
    public class CreateTeamApplicationRequestDto : BaseRequestDto
    {
        [Required]
        public string? Text { get; set; }
    }
}
