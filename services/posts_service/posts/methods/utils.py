
from django.http import Http404
from django.core.exceptions import ValidationError


def check_request_method(request, method):
    if request.method != method:
        raise Http404(f"Only {method} requests are allowed")


def validate_array_of_integers(array, name):
    if not isinstance(array, list) or not all(isinstance(item, int) for item in array):
        raise ValidationError(f"{name} must be a list of integers.")
