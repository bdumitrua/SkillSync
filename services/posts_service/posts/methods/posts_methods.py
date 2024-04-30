import json
from django.http import JsonResponse
from django.shortcuts import get_object_or_404
from django.db.models import Count

from posts.models import Post
from posts.forms import UpdatePostForm, CreatePostForm
from posts.serializers import PostSerializer
from .utils import *

# Request methods


def posts_index(request):
    check_request_method(request, 'GET')

    all_posts = Post.objects.annotate(likes_count=Count('postlike')).all()

    serializer = PostSerializer(all_posts, many=True)

    return JsonResponse(serializer.data, safe=False)


def posts_show(request, id):
    check_request_method(request, 'GET')

    post = get_object_or_404(Post.objects.annotate(
        likes_count=Count('postlike')), id=id)
    serializer = PostSerializer(post)

    return JsonResponse(serializer.data)


def auth_posts_create(request):
    check_request_method(request, 'POST')

    data = json.loads(request.body)
    form = CreatePostForm(data)

    if not form.is_valid():
        return JsonResponse({'errors': form.errors}, status=422)

    post = create_post_model(data)

    return JsonResponse({
        'message': 'Post created successfully', 'post_id': post.id
    })


def auth_posts_update(request, id):
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


def auth_posts_delete(request, id):
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
