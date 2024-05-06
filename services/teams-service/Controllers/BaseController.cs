using System.Security.Claims;
using Microsoft.AspNetCore.Mvc;
using TeamsService.Exceptions;
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

        // TODO
        // REMOVE USER ID
        protected async Task AuthorizedUserIsModerator(int teamId, int userId)
        {
            TeamMember? authorizedUserMembership = await TeamMemberRepository.GetMemberByBothIds(
                teamId,
                userId
            );

            // Not a member or isn't moderator
            if (authorizedUserMembership == null || authorizedUserMembership.IsModerator == false)
                throw new InsufficientRightsException();
        }

        // TODO
        // REMOVE USER ID
        protected async Task<bool> AuthorizedUserIsMember(int teamId, int userId)
        {
            TeamMember? authorizedUserMembership = await TeamMemberRepository.GetMemberByBothIds(
                teamId,
                userId
            );

            return authorizedUserMembership != null;
        }

        protected ActionResult Forbidden(object? value = null)
        {
            return StatusCode(StatusCodes.Status403Forbidden, value);
        }
    }
}
