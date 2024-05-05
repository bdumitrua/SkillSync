using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamVacancyDto
{
    public class UpdateTeamVacancyRequestDto : BaseRequestDto
    {
        [Required]
        public string Title { get; set; } = string.Empty;

        [Required]
        public string Description { get; set; } = string.Empty;
    }
}
