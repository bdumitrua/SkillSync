using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc.ModelBinding;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Models;

namespace TeamsService.ModelBinders
{
    public class TeamEntityBinder : IModelBinder
    {
        private readonly ApplicationDBContext _context;

        public TeamEntityBinder(ApplicationDBContext context)
        {
            _context = context;
        }

        public async Task BindModelAsync(ModelBindingContext bindingContext)
        {
            var modelName = "team";
            var routeValue = bindingContext.HttpContext.Request.RouteValues[$"{modelName}Id"];

            if (routeValue == null)
                return;

            if (!int.TryParse(routeValue.ToString(), out int teamId))
            {
                bindingContext.ModelState.AddModelError(modelName, "ID must be an integer.");
                bindingContext.Result = ModelBindingResult.Failed();
                return;
            }

            var team = await _context.Teams.FindAsync(teamId);
            if (team == null)
            {
                bindingContext.HttpContext.Response.StatusCode = 404;
                bindingContext.HttpContext.Response.ContentType = "application/json";
                await bindingContext.HttpContext.Response.WriteAsync(
                    "{\"error\": \"Team not found\"}"
                );
                await bindingContext.HttpContext.Response.CompleteAsync();
                bindingContext.Result = ModelBindingResult.Success(null);
                return;
            }

            bindingContext.Result = ModelBindingResult.Success(team);
            return;
        }
    }
}
