import json
from django.http import HttpResponse, JsonResponse, Http404
from django.core.exceptions import ObjectDoesNotExist
from django.shortcuts import get_object_or_404
from .models import Post
from .forms import UpdatePostForm, CreatePostForm

# Request methods


def index(request):
    all_posts = Post.objects.all()
    posts_list = list(all_posts.values())

    return JsonResponse(posts_list, safe=False)


def create(request):
    check_request_method(request, 'POST')

    data = json.loads(request.body)
    form = CreatePostForm(data)

    if not form.is_valid():
        return JsonResponse({'errors': form.errors}, status=422)

    post = create_post_model(data)

    return JsonResponse({
        'message': 'Post created successfully', 'post_id': post.id
    })


def update(request, id):
    check_request_method(request, 'PUT')

    data = json.loads(request.body)
    form = UpdatePostForm(data)

    if not form.is_valid():
        return JsonResponse({'errors': form.errors}, status=422)

    post = get_object_or_404(Post, id=id)
    update_post_model(post, data)

    return JsonResponse({'message': 'Post updated successfully', 'post_id': post.id})


def delete(request, id):
    check_request_method(request, 'DELETE')

    post = get_object_or_404(Post, id=id)
    post.delete()

    return JsonResponse({'message': 'Post deleted successfully', 'post_id': id})


# Additional functions

def update_post_model(post, data):
    post.text = data.get('text')
    post.media_url = data.get('media_url')
    post.save()


def create_post_model(data):
    return Post.objects.create(
        text=data.get('text'),
        media_url=data.get('media_url'),
        entity_type=data.get('entity_type'),
        entity_id=data.get('entity_id'),
    )


def check_request_method(request, method):
    if request.method != method:
        raise Http404(f"Only {method} requests are allowed")
