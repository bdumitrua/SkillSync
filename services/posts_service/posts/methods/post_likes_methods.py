from django.http import JsonResponse
from django.core.exceptions import ObjectDoesNotExist

from posts.models import Post, PostLike
from posts.serializers import PostSerializer

from .utils import *
# Request methods


def user_likes(request, user_id):
    check_request_method(request, 'GET')

    liked_posts_ids = get_user_liked_posts_ids(user_id)
    posts_data = get_posts_data(liked_posts_ids)
    serializer = PostSerializer(posts_data, many=True)

    return JsonResponse(serializer.data, safe=False)


def likes_create(request, post_id):
    check_request_method(request, 'POST')

    if get_post_like(post_id, request.user_id):
        return JsonResponse({
            'message': 'You already liked this post'
        }, status=200)

    post = get_post(post_id)
    like = create_like_model(post.id, request.user_id)

    return JsonResponse({
        'message': f"Succesfully liked post ID: {post.id}"
    }, status=201)


def likes_remove(request, post_id):
    check_request_method(request, 'DELETE')

    post = get_post(post_id)
    post_like = get_post_like(post.id, request.user_id)

    if not post_like:
        return JsonResponse({
            'message': 'You haven\'t liked this post'
        }, status=400)

    post_like.delete()

    return JsonResponse({
        'message': f"Succesfully removed like from post ID: {post.id}"
    })


# Additional functions


def create_like_model(post_id, user_id):
    return PostLike.objects.create(
        post_id=post_id,
        user_id=user_id,
    )
