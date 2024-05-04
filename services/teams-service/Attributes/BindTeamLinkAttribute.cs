using Microsoft.AspNetCore.Mvc;
using TeamsService.ModelBinders;

namespace TeamsService.Attributes
{
    public class BindTeamLinkAttribute : ModelBinderAttribute
    {
        public BindTeamLinkAttribute()
            : base(typeof(TeamLinkBinder)) { }
    }
}
