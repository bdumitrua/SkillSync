using Microsoft.AspNetCore.Mvc;
using TeamsService.ModelBinders;

namespace TeamsService.Attributes
{
    public class BindTeamScopeAttribute : ModelBinderAttribute
    {
        public BindTeamScopeAttribute()
            : base(typeof(TeamScopeBinder)) { }
    }
}
