import json
from django.http import JsonResponse
from django.core.exceptions import ObjectDoesNotExist
from django.shortcuts import get_object_or_404

from posts.models import Post, PostComment, PostCommentLike
from .utils import *
from posts.serializers import PostCommentSerializer
from posts.forms import CreatePostCommentForm


def auth_post_comments(request, post_id):
    check_request_method(request, 'GET')

    post_comments = PostComment.objects.filter(post_id=post_id)
    serializer = PostCommentSerializer(post_comments, many=True)

    return JsonResponse(serializer.data, safe=False)


def auth_comments_create(request, post_id):
    check_request_method(request, 'POST')

    data = json.loads(request.body)
    form = CreatePostCommentForm(data)

    if not form.is_valid():
        return JsonResponse({'errors': form.errors}, status=422)

    post = get_object_or_404(Post, id=post_id)
    new_comment = create_comment(data, post_id, request.user_id)

    return JsonResponse({
        'message': 'Comment created successfully', 'comment_id': new_comment.id
    })


def auth_comments_remove(request, comment_id):
    check_request_method(request, 'DELETE')

    comment = get_object_or_404(PostComment, id=comment_id)

    if not (user_own_comment(comment, request.user_id)):
        return JsonResponse({'error': 'Insufficient rights'}, status=403)

    comment.delete()

    return JsonResponse({'message': 'Comment deleted successfully', 'comment_id': comment_id})


def auth_comments_like(request, comment_id):
    check_request_method(request, 'POST')
    get_object_or_404(PostComment, id=comment_id)

    if comment_like_exists(request.user_id, comment_id):
        return JsonResponse({
            'message': 'You already liked this comment'
        }, status=200)

    new_comment_like = create_comment_like(request.user_id, comment_id)

    return JsonResponse({
        'message': f"Succesfully liked comment ID: {comment_id}"
    }, status=201)


def auth_comments_unlike(request, comment_id):
    check_request_method(request, 'DELETE')
    get_object_or_404(PostComment, id=comment_id)

    comment_like = PostCommentLike.objects.filter(
        user_id=request.user_id,
        post_comment_id=comment_id)

    if len(comment_like) < 1:
        return JsonResponse({
            'message': 'You haven\'t liked this comment'
        }, status=400)

    comment_like.delete()

    return JsonResponse({'message': f"Succesfully unliked comment ID: {comment_id}"})


# Additional functions

def user_own_comment(comment, user_id):
    return comment.user_id == user_id


def comment_like_exists(user_id, comment_id):
    try:
        like = PostCommentLike.objects.get(
            post_comment_id=comment_id, user_id=user_id)
        return True
    except ObjectDoesNotExist:
        return False


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
