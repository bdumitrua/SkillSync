using System.Security.Claims;
using Microsoft.AspNetCore.Mvc;
using TeamsService.Intefaces.Repository;
using TeamsService.Models;

namespace TeamsService.Controllers
{
    public abstract class BaseController : ControllerBase
    {
        [FromServices]
        public required ITeamMemberRepository TeamMemberRepository { get; set; }

        protected int GetAuthorizedUserId()
        {
            string? jwtUserId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;

            if (!int.TryParse(jwtUserId, out int authorizedUserId))
            {
                throw new UnauthorizedAccessException("Invalid token data, contact tech support.");
            }

            return authorizedUserId;
        }

        protected async Task<bool> AuthorizedUserIsModerator(int teamId, int userId)
        {
            TeamMember? authorizedUserMembership = await TeamMemberRepository.GetMemberByBothIds(
                teamId,
                userId
            );

            // Not a member
            if (authorizedUserMembership == null)
                return false;

            return authorizedUserMembership.IsModerator;
        }

        protected ActionResult Forbidden(object? value = null)
        {
            return StatusCode(StatusCodes.Status403Forbidden, value);
        }
    }
}
