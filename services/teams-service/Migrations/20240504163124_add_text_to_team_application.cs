using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace teams_service.Migrations
{
    /// <inheritdoc />
    public partial class add_text_to_team_application : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<string>(
                name: "Text",
                table: "TeamApplications",
                type: "longtext",
                nullable: true)
                .Annotation("MySql:CharSet", "utf8mb4");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "Text",
                table: "TeamApplications");
        }
    }
}
