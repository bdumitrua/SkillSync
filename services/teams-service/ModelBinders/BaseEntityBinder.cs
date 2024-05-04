using System.Text.Json;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc.ModelBinding;
using Microsoft.EntityFrameworkCore;
using TeamsService.Data;
using TeamsService.Models;

namespace TeamsService.ModelBinders
{
    public abstract class BaseEntityBinder : IModelBinder
    {
        protected readonly ApplicationDBContext _context;
        protected readonly string _modelName;
        protected readonly string _routeKey;

        public BaseEntityBinder(ApplicationDBContext context, string modelName, string routeKey)
        {
            _context = context;
            _modelName = modelName;
            _routeKey = routeKey;
        }

        public async Task BindModelAsync(ModelBindingContext bindingContext)
        {
            var routeValue = bindingContext.HttpContext.Request.RouteValues[_routeKey];

            if (routeValue == null)
                return;

            if (!int.TryParse(routeValue.ToString(), out int modelId))
            {
                bindingContext.ModelState.AddModelError(_modelName, "ID must be an integer.");
                bindingContext.Result = ModelBindingResult.Failed();
                return;
            }

            var modelData = await GetModelDataAsync(modelId);
            if (modelData == null)
            {
                bindingContext.ModelState.AddModelError(_modelName, $"{_modelName} not found.");
                bindingContext.Result = ModelBindingResult.Failed();
                return;
            }

            bindingContext.Result = ModelBindingResult.Success(modelData);
            return;
        }

        protected abstract Task<BaseModel?> GetModelDataAsync(int modelId);
    }
}
