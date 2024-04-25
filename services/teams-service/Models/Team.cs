namespace TeamsService.Models
{
    public class Team : BaseModel
    {
        public string Name { get; set; } = string.Empty;
        public string Avatar { get; set; } = string.Empty;
        public string Description { get; set; } = string.Empty;
        public List<TeamMember> TeamMembers { get; set; } = new List<TeamMember>();
    }
}
