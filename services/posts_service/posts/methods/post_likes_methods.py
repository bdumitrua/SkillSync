from django.http import JsonResponse
from django.core.exceptions import ObjectDoesNotExist
from django.shortcuts import get_object_or_404

from posts.models import Post, PostLike
from .utils import *

# Request methods


def auth_likes_create(request, id):
    check_request_method(request, 'POST')

    user_id = request.user_id
    if like_exists(id, user_id):
        return JsonResponse({
            'message': 'You already liked this post'
        }, status=204)

    post = get_object_or_404(Post, id=id)
    like = create_like_model(post.id, user_id)

    return JsonResponse({
        'message': f"Succesfully liked post ID: {id}"
    })


def auth_likes_remove(request, id):
    check_request_method(request, 'POST')

    post = get_object_or_404(Post, id=id)
    like = get_object_or_404(PostLike, post_id=post.id,
                             user_id=request.user_id)
    like.delete()

    return JsonResponse({
        'message': f"Succesfully removed like from post ID: {id}"
    })


# Additional functions

def like_exists(post_id, user_id):
    try:
        like = PostLike.objects.get(post_id=post_id, user_id=user_id)
        return True
    except ObjectDoesNotExist:
        return False


def create_like_model(post_id, user_id):
    return PostLike.objects.create(
        post_id=post_id,
        user_id=user_id,
    )
