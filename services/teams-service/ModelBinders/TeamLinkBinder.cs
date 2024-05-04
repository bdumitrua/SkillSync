using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc.ModelBinding;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Models;

namespace TeamsService.ModelBinders
{
    public class TeamLinkBinder : BaseEntityBinder
    {
        public TeamLinkBinder(ApplicationDBContext context)
            : base(context, "teamLink", "teamLinkId") { }

        protected override async Task<BaseModel?> GetModelDataAsync(int modelId)
        {
            return await _context.TeamLinks.FindAsync(modelId);
        }
    }
}
