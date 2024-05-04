using Microsoft.AspNetCore.Mvc;
using TeamsService.ModelBinders;

namespace TeamsService.Attributes
{
    public class BindTeamVacancyAttribute : ModelBinderAttribute
    {
        public BindTeamVacancyAttribute()
            : base(typeof(TeamVacancyBinder)) { }
    }
}
