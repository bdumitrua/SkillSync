import json
from django.http import JsonResponse
from django.core.exceptions import ObjectDoesNotExist

from posts.models import Post, PostComment, PostCommentLike
from posts.serializers import PostCommentSerializer
from posts.forms import CreatePostCommentForm

from .utils import *


def post_comments(request, post_id):
    check_request_method(request, 'GET')

    post_comments = get_post_comments(post_id)
    serializer = PostCommentSerializer(post_comments, many=True)

    return JsonResponse(serializer.data, safe=False)


def comments_create(request, post_id):
    check_request_method(request, 'POST')

    data = json.loads(request.body)
    form = CreatePostCommentForm(data)

    if not form.is_valid():
        return JsonResponse({'errors': form.errors}, status=422)

    post = get_post(post_id)
    new_comment = create_comment(data, post_id, request.user_id)

    return JsonResponse({
        'message': 'Comment created successfully', 'comment_id': new_comment.id
    })


def comments_remove(request, comment_id):
    check_request_method(request, 'DELETE')

    comment = get_comment(comment_id)

    if not (user_own_comment(comment, request.user_id)):
        return JsonResponse({'error': 'Insufficient rights'}, status=403)

    comment.delete()

    return JsonResponse({'message': 'Comment deleted successfully', 'comment_id': comment_id})


def comments_like(request, comment_id):
    check_request_method(request, 'POST')
    get_comment(comment_id)

    if get_comment_like(comment_id, request.user_id):
        return JsonResponse({
            'message': 'You already liked this comment'
        }, status=200)

    new_comment_like = create_comment_like(request.user_id, comment_id)

    return JsonResponse({
        'message': f"Succesfully liked comment ID: {comment_id}"
    }, status=201)


def comments_unlike(request, comment_id):
    check_request_method(request, 'DELETE')
    get_comment(comment_id)

    comment_like = get_comment_like(comment_id, request.user_id)

    if not comment_like:
        return JsonResponse({
            'message': 'You haven\'t liked this comment'
        }, status=400)

    comment_like.delete()

    return JsonResponse({'message': f"Succesfully unliked comment ID: {comment_id}"})


# Additional

def user_own_comment(comment, user_id):
    return comment.user_id == user_id


def create_comment(data, post_id, user_id):
    return PostComment.objects.create(
        post_id=post_id,
        user_id=user_id,
        text=data.get("text"),
        media_url=data.get("media_url"),
    )


def create_comment_like(user_id, comment_id):
    return PostCommentLike.objects.create(
        user_id=user_id,
        post_comment_id=comment_id
    )
