using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc.ModelBinding;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Models;

namespace TeamsService.ModelBinders
{
    public class TeamVacancyBinder : BaseEntityBinder
    {
        public TeamVacancyBinder(ApplicationDBContext context)
            : base(context, "teamVacancy", "teamVacancyId") { }

        protected override async Task<BaseModel?> GetModelDataAsync(int modelId)
        {
            return await _context.TeamVacancies.FindAsync(modelId);
        }
    }
}
