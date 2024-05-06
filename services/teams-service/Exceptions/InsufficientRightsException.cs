using System;
using System.Text.Json;

namespace TeamsService.Exceptions
{
    public class InsufficientRightsException : Exception
    {
        public int StatusCode { get; } = 403;

        public InsufficientRightsException()
            : base("You do not have permission to perform this action.") { }

        public InsufficientRightsException(object messageObject)
            : base(JsonSerializer.Serialize(messageObject)) { }
    }
}
