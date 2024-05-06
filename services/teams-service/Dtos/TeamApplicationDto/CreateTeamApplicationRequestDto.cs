using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamApplicationDto
{
    public class CreateTeamApplicationRequestDto : BaseRequestDto
    {
        public string? Text { get; set; }

        [Required]
        public int TeamId { get; set; }

        [Required]
        public int VacancyId { get; set; }
    }
}
