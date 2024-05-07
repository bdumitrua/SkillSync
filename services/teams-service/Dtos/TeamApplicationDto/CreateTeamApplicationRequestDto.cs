using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamApplicationDto
{
    public class CreateTeamApplicationRequestDto : BaseRequestDto
    {
        [StringLength(200, ErrorMessage = "The text cannot be longer than 200 characters.")]
        public string? Text { get; set; }

        [Required(ErrorMessage = "TeamId is required.")]
        [Range(1, int.MaxValue, ErrorMessage = "TeamId must be greater than 0.")]
        public int TeamId { get; set; }

        [Required(ErrorMessage = "VacancyId is required.")]
        [Range(1, int.MaxValue, ErrorMessage = "VacancyId must be greater than 0.")]
        public int VacancyId { get; set; }
    }
}
