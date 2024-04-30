
from django.http import Http404


def check_request_method(request, method):
    if request.method != method:
        raise Http404(f"Only {method} requests are allowed")
