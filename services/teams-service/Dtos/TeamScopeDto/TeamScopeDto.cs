using System.ComponentModel.DataAnnotations;

namespace TeamsService.Dtos.TeamScopeDto
{
    public class TeamScopeDto : BaseRequestDto
    {
        public string Title { get; set; } = string.Empty;
        public int TeamId { get; set; }
        public DateTime CreatedAt { get; set; }
    }
}
