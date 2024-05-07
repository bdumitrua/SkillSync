using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamApplicationDto
{
    public class UpdateTeamApplicationRequestDto : BaseRequestDto
    {
        [StringLength(20, ErrorMessage = "Status must be 20 characters or fewer.")]
        public string? Status { get; set; }
    }
}
