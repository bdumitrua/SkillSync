using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamVacancyDto
{
    public class UpdateTeamVacancyRequestDto : BaseRequestDto
    {
        [Required(ErrorMessage = "Title is required.")]
        [MaxLength(30, ErrorMessage = "Title can't exceed 30 characters.")]
        public string Title { get; set; } = string.Empty;

        [Required(ErrorMessage = "Description is required.")]
        [MaxLength(500, ErrorMessage = "Description can't exceed 500 characters.")]
        public string Description { get; set; } = string.Empty;
    }
}
