using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc.ModelBinding;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Models;

namespace TeamsService.ModelBinders
{
    public class TeamEntityBinder : BaseEntityBinder
    {
        public TeamEntityBinder(ApplicationDBContext context)
            : base(context, "team", "teamId") { }

        protected override async Task<BaseModel?> GetModelDataAsync(int modelId)
        {
            return await _context.Teams.FindAsync(modelId);
        }
    }
}
