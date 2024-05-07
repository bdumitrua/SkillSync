using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamScopeDto
{
    public class CreateTeamScopeRequestDto : BaseRequestDto
    {
        [Required(ErrorMessage = "Title is required.")]
        [MaxLength(20, ErrorMessage = "Title can't exceed 20 characters.")]
        public string Title { get; set; } = string.Empty;
    }
}
