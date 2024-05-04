using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamVacancyDto
{
    public class CreateTeamVacancyRequestDto : BaseRequestDto
    {
        [Required]
        public string Title { get; set; } = string.Empty;

        [Required]
        public string Description { get; set; } = string.Empty;

        [Required]
        public int TeamId { get; set; }
    }
}
