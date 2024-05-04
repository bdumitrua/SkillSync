using Microsoft.AspNetCore.Mvc;
using TeamsService.ModelBinders;

namespace TeamsService.Attributes
{
    public class BindTeamAttribute : ModelBinderAttribute
    {
        public BindTeamAttribute()
            : base(typeof(TeamEntityBinder)) { }
    }
}
