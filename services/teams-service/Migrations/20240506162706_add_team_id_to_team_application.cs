using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace teams_service.Migrations
{
    /// <inheritdoc />
    public partial class add_team_id_to_team_application : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<int>(
                name: "TeamId",
                table: "TeamApplications",
                type: "int",
                nullable: false,
                defaultValue: 0);
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "TeamId",
                table: "TeamApplications");
        }
    }
}
