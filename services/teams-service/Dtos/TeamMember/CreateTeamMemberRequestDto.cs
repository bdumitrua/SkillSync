using System.ComponentModel.DataAnnotations;
using TeamsService.Models;

namespace TeamsService.Dtos.TeamMemberDto
{
    public class CreateTeamMemberRequestDto : BaseRequestDto
    {
        [Required]
        public int UserId { get; set; }

        [Required]
        public bool IsModerator { get; set; }
    }
}
