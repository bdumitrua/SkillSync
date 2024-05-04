using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamScopeDto
{
    public class CreateTeamScopeRequestDto : BaseRequestDto
    {
        [Required]
        public string Title { get; set; } = string.Empty;

        [Required]
        public int TeamId { get; set; }
    }
}
