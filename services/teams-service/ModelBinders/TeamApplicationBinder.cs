using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc.ModelBinding;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Models;

namespace TeamsService.ModelBinders
{
    public class TeamApplicationBinder : BaseEntityBinder
    {
        public TeamApplicationBinder(ApplicationDBContext context)
            : base(context, "teamApplication", "teamApplicationId") { }

        protected override async Task<BaseModel?> GetModelDataAsync(int modelId)
        {
            return await _context.TeamApplications.FindAsync(modelId);
        }
    }
}
