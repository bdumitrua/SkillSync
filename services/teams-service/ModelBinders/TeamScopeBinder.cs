using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc.ModelBinding;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Models;

namespace TeamsService.ModelBinders
{
    public class TeamScopeBinder : BaseEntityBinder
    {
        public TeamScopeBinder(ApplicationDBContext context)
            : base(context, "teamScope", "teamScopeId") { }

        protected override async Task<BaseModel?> GetModelDataAsync(int modelId)
        {
            return await _context.TeamScopes.FindAsync(modelId);
        }
    }
}
