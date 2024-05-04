using Microsoft.AspNetCore.Mvc;
using TeamsService.ModelBinders;

namespace TeamsService.Attributes
{
    public class BindTeamApplicationAttribute : ModelBinderAttribute
    {
        public BindTeamApplicationAttribute()
            : base(typeof(TeamApplicationBinder)) { }
    }
}
