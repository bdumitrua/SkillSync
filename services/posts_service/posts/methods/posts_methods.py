import json
from django.http import JsonResponse
from django.shortcuts import get_object_or_404
from django.db.models import Count
from django.db import connection

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


def show_post(request, id):
    check_request_method(request, 'GET')

    post = get_object_or_404(Post.objects.annotate(
        likes_count=Count('postlike')), id=id)
    serializer = PostSerializer(post)

    return JsonResponse(serializer.data)


def user_posts(request, user_id):
    check_request_method(request, 'GET')

    user_posts = Post.objects.filter(entity_type="user", entity_id=user_id)
    serializer = PostSerializer(user_posts, many=True)

    return JsonResponse(serializer.data, safe=False)


def team_posts(request, team_id):
    check_request_method(request, 'GET')

    team_posts = Post.objects.filter(entity_type="team", entity_id=team_id)
    serializer = PostSerializer(team_posts, many=True)

    return JsonResponse(serializer.data, safe=False)


def posts_feed(request):
    check_request_method(request, 'GET')

    # Получаем массивы из GET-параметров
    teams_str = request.GET.get('teams_id', '[]')
    users_str = request.GET.get('users_id', '[]')

    try:
        teams = json.loads(teams_str)
        users = json.loads(users_str)

        validate_array_of_integers(teams, 'teams_id')
        validate_array_of_integers(users, 'users_id')
    except json.JSONDecodeError:
        return JsonResponse({'error': 'Invalid JSON format.'}, status=400)
    except ValidationError as e:
        return JsonResponse({'error': str(e)}, status=400)

    if not teams and not users:
        return JsonResponse({'error': 'Empty team and user IDs.'}, status=400)

    try:
        with connection.cursor() as cursor:
            # Подготовка кортежей для SQL IN
            teams_tuple = tuple(teams) if teams else (-1,)
            users_tuple = tuple(users) if users else (-1,)
            query = """
            SELECT * FROM posts_post 
            WHERE (entity_type = 'user' AND entity_id IN %s)
               OR (entity_type = 'team' AND entity_id IN %s)
            ORDER BY created_at DESC
            """
            cursor.execute(query, [users_tuple, teams_tuple])

            rows = cursor.fetchall()
            columns = [col[0] for col in cursor.description]
            results = [dict(zip(columns, row)) for row in rows]
    except Exception as e:
        return JsonResponse({'error': str(e)}, status=500)

    serializer = PostSerializer(results, many=True)

    return JsonResponse(serializer.data, safe=False)


def posts_create(request):
    check_request_method(request, 'POST')

    data = json.loads(request.body)
    form = CreatePostForm(data)

    if not form.is_valid():
        return JsonResponse({'errors': form.errors}, status=422)

    post = create_post_model(data)

    return JsonResponse({
        'message': 'Post created successfully', 'post_id': post.id
    })


def posts_update(request, id):
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


def posts_delete(request, id):
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
