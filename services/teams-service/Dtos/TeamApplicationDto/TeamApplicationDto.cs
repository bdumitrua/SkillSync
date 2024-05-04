using TeamsService.Models;

namespace TeamsService.Dtos.TeamApplicationDto
{
    public class TeamApplicationDto
    {
        public int Id { get; set; }
        public string Status { get; set; } = string.Empty;
        public string? Text { get; set; }
        public int UserId { get; set; }
        public int VacancyId { get; set; }
        public TeamVacancy? TeamVacancy { get; set; }
    }
}
