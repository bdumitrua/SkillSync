using TeamsService.Dtos;
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
                var value = property.GetValue(requestDto); // Используйте экземпляр DTO, а не Type
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
                object? value = property.GetValue(requestDto);
                if (value != null)
                {
                    var entityModelProperty = entityModelType.GetProperty(property.Name);
                    if (
                        entityModelProperty != null
                        && entityModelProperty.CanWrite
                        && entityModelProperty.PropertyType == property.PropertyType
                    )
                    {
                        try
                        {
                            entityModelProperty.SetValue(entityModel, value);
                        }
                        catch (Exception ex)
                        {
                            Console.WriteLine(
                                $"Error setting property {property.Name}: {ex.Message}"
                            );
                        }
                    }
                }
            }
        }
    }
}
