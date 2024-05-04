namespace TeamsService.Models
{
    public class TeamApplication : BaseModel
    {
        public int UserId { get; set; }
        public int VacancyId { get; set; }
        public string? Text { get; set; }
        public string Status { get; set; } = "Sent";
        public TeamVacancy? TeamVacancy { get; set; }
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
    }
}
