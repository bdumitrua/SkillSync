using System.Text;
using Microsoft.IdentityModel.Tokens;
using TeamsService.Intefaces;

namespace TeamsService.Services
{
    public class TokenService : ITokenService
    {
        private readonly IConfiguration _config;
        private readonly SymmetricSecurityKey _key;

        public TokenService(IConfiguration config)
        {
            _config = config;
#pragma warning disable CS8604 // Возможно, аргумент-ссылка, допускающий значение NULL.
            _key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(_config["JWT:SigningKey"]));
#pragma warning restore CS8604 // Возможно, аргумент-ссылка, допускающий значение NULL.
        }
    }
}
