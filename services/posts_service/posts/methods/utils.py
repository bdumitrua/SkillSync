from django.core.exceptions import ValidationError

from posts.models import Post, PostComment, PostCommentLike, PostLike
from posts.exceptions import ModelNotFoundException, InvalidRequestMethodException


def check_request_method(request, method):
    if request.method != method:
        raise InvalidRequestMethodException(
            f"Only {method} requests are allowed to this route")


def validate_array_of_integers(array, name):
    if not isinstance(array, list) or not all(isinstance(item, int) for item in array):
        raise ValidationError(f"{name} must be a list of integers.")


def get_post(post_id):
    try:
        return Post.objects.get(id=post_id)
    except Post.DoesNotExist:
        raise ModelNotFoundException("Post not found")


def get_comment(comment_id):
    try:
        return PostComment.objects.get(id=comment_id)
    except PostComment.DoesNotExist:
        raise ModelNotFoundException("Post comment not found")


def get_post_like(post_id, user_id):
    try:
        return PostLike.objects.get(post_id=post_id, user_id=user_id)
    except PostLike.DoesNotExist:
        return None


def get_comment_like(comment_id, user_id):
    try:
        return PostCommentLike.objects.get(post_comment_id=comment_id, user_id=user_id)
    except PostCommentLike.DoesNotExist:
        return None


def get_post_comments(post_id):
    return PostComment.objects.filter(post_id=post_id)


def get_posts_data(posts_ids):
    return Post.objects.filter(id__in=posts_ids)


def get_user_liked_posts_ids(user_id):
    likes = PostLike.objects.filter(user_id=user_id)
    liked_posts_ids = [like.post_id for like in likes]

    return liked_posts_ids
