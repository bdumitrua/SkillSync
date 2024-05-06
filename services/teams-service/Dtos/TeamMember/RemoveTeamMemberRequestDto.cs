using System.ComponentModel.DataAnnotations;
using TeamsService.Models;

namespace TeamsService.Dtos.TeamMemberDto
{
    public class RemoveTeamMemberRequestDto : BaseRequestDto
    {
        [Required]
        public int UserId { get; set; }
    }
}
