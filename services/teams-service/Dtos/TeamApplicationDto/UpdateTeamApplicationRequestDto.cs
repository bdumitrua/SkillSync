using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamApplicationDto
{
    public class UpdateTeamApplicationRequestDto : BaseRequestDto
    {
        public string? Status { get; set; }
    }
}
