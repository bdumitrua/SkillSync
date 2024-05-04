using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamApplicationDto
{
    public class UpdateTeamApplicationRequestDto : BaseRequestDto
    {
        public string? Text { get; set; }
        public string? Status { get; set; }
    }
}
