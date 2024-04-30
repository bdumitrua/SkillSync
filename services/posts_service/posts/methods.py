import json
from django.http import HttpResponse, JsonResponse, Http404
from django.core.exceptions import ObjectDoesNotExist
from django.shortcuts import get_object_or_404
from .models import Post
from .forms import UpdatePostForm, CreatePostForm
from .serializers import PostSerializer

# Request methods


def index(request):
    check_request_method(request, 'GET')

    all_posts = Post.objects.all()

    serializer = PostSerializer(all_posts, many=True)

    return JsonResponse(serializer.data, safe=False)


def show(request, id):
    check_request_method(request, 'GET')

    post = get_object_or_404(Post, id=id)
    serializer = PostSerializer(post)

    return JsonResponse(serializer.data)


def auth_create(request):
    check_request_method(request, 'POST')

    data = json.loads(request.body)
    form = CreatePostForm(data)

    if not form.is_valid():
        return JsonResponse({'errors': form.errors}, status=422)

    post = create_post_model(data)

    return JsonResponse({
        'message': 'Post created successfully', 'post_id': post.id
    })


def auth_update(request, id):
    check_request_method(request, 'PUT')

    data = json.loads(request.body)
    form = UpdatePostForm(data)

    if not form.is_valid():
        return JsonResponse({'errors': form.errors}, status=422)

    post = get_object_or_404(Post, id=id)
    if not user_own_post(post, request.user_id):
        return JsonResponse('Insufficient rights', status=403)

    update_post_model(post, data)

    return JsonResponse({'message': 'Post updated successfully', 'post_id': post.id})


def auth_delete(request, id):
    check_request_method(request, 'DELETE')

    post = get_object_or_404(Post, id=id)

    if not user_own_post(post, request.user_id):
        return JsonResponse({'error': 'Insufficient rights'}, status=403)

    post.delete()

    return JsonResponse({'message': 'Post deleted successfully', 'post_id': id})


# Additional functions

def user_own_post(post, user_id):
    if (post.entity_type == 'team'):
        # TODO
        return True
    else:
        return post.entity_id == user_id


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
