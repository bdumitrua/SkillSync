using Microsoft.EntityFrameworkCore;
using TeamsService.Models;

namespace TeamsService.Data
{
    public class ApplicationDBContext : DbContext
    {
        public ApplicationDBContext(DbContextOptions dbContextOptions)
            : base(dbContextOptions) { }

        public DbSet<Team> Teams { get; set; }
        public DbSet<TeamMember> TeamMembers { get; set; }
        public DbSet<TeamScope> TeamScopes { get; set; }
        public DbSet<TeamLink> TeamLinks { get; set; }
        public DbSet<TeamVacancy> TeamVacancies { get; set; }
        public DbSet<TeamApplication> TeamApplications { get; set; }
    }
}
