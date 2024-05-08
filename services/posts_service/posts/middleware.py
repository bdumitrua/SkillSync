import jwt
from django.conf import settings
from django.http import JsonResponse


class JWTMiddleware:
    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        response = self.get_response(request)
        return response

    def process_view(self, request, view_func, view_args, view_kwargs):
        # Если добавить, срабаывать будет только на методах, начинающихся с auth_
        # if view_func.__name__.startswith('auth_'):

        auth_header = request.headers.get('Authorization')
        if not auth_header:
            return JsonResponse({'error': 'Authorization header is required'}, status=401)

        try:
            token = auth_header.split(' ')[1]
            decoded_token = jwt.decode(
                token, settings.SECRET_KEY, algorithms=["HS256"])

            request.user_id = decoded_token['userId']
            request.user_email = decoded_token['email']
        except jwt.DecodeError:
            return JsonResponse({'error': 'Invalid token'}, status=401)
        except jwt.ExpiredSignatureError:
            return JsonResponse({'error': 'Token has expired'}, status=401)
