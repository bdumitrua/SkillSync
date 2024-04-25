using TeamsService.Dtos;
using TeamsService.Dtos.Team;
using TeamsService.Models;

namespace TeamsService.Mappers
{
    public static class DtoMapper
    {
        public static bool IsEmpty(this BaseRequestDto requestDto)
        {
            var requestDtoType = requestDto.GetType();
            foreach (var property in requestDtoType.GetProperties())
            {
                var value = property.GetValue(requestDtoType);
                if (value != null)
                {
                    return false;
                }
            }
            return true;
        }

        public static void UpdateModelFromDto(this BaseModel entityModel, BaseRequestDto requestDto)
        {
            var entityModelType = entityModel.GetType();
            var requestDtoType = requestDto.GetType();

            foreach (var property in requestDtoType.GetProperties())
            {
                var value = property.GetValue(requestDto);
                if (value != null)
                {
                    var entityModelProperty = entityModelType.GetProperty(property.Name);
                    if (
                        entityModelProperty != null
                        && entityModelProperty.PropertyType == property.PropertyType
                    )
                    {
                        entityModelProperty.SetValue(entityModel, value);
                    }
                }
            }
        }
    }
}
