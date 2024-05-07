using System.ComponentModel.DataAnnotations;
using TeamsService.Models;

namespace TeamsService.Dtos.TeamMemberDto
{
    public class CreateTeamMemberRequestDto : BaseRequestDto
    {
        [Required(ErrorMessage = "UserId is required.")]
        [Range(1, int.MaxValue, ErrorMessage = "UserId must be greater than 0.")]
        public int UserId { get; set; }

        [Required(ErrorMessage = "IsModerator is required.")]
        public bool IsModerator { get; set; }
    }
}
