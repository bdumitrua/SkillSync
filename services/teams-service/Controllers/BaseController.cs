using System.Security.Claims;
using Microsoft.AspNetCore.Mvc;

namespace TeamsService.Controllers
{
    public abstract class BaseController : ControllerBase
    {
        protected int GetAuthorizedUserId()
        {
            string? jwtUserId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;

            if (!int.TryParse(jwtUserId, out int authorizedUserId))
            {
                throw new UnauthorizedAccessException("Invalid token data, contact tech support.");
            }

            return authorizedUserId;
        }
    }
}
