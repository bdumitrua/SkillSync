using System.ComponentModel.DataAnnotations;
using TeamsService.Models;

namespace TeamsService.Dtos.TeamMemberDto
{
    public class UpdateTeamMemberRequestDto : BaseRequestDto
    {
        [Required(ErrorMessage = "UserId is required.")]
        [Range(1, int.MaxValue, ErrorMessage = "UserId must be greater than 0.")]
        public int UserId { get; set; }

        [Required(ErrorMessage = "IsModerator is required.")]
        public bool IsModerator { get; set; }

        [Required(ErrorMessage = "TeamId is required.")]
        [Range(1, int.MaxValue, ErrorMessage = "TeamId must be greater than 0.")]
        public int TeamId { get; set; }

        [MaxLength(80, ErrorMessage = "About can't exceed 80 characters.")]
        public string? About { get; set; }
    }
}
