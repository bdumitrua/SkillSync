using System.ComponentModel.DataAnnotations;
using TeamsService.Models;

namespace TeamsService.Dtos.TeamMemberDto
{
    public class UpdateTeamMemberRequestDto : BaseRequestDto
    {
        [Required]
        public int UserId { get; set; }

        [Required]
        public int TeamId { get; set; }

        [Required]
        public bool IsModerator { get; set; }
        public string? About { get; set; }
    }
}
