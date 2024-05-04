using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamVacancyDto
{
    public class TeamVacancyDto : BaseRequestDto
    {
        [Required]
        public int TeamId { get; set; }

        [Required]
        public string Title { get; set; } = string.Empty;

        [Required]
        public string Description { get; set; } = string.Empty;
    }
}
