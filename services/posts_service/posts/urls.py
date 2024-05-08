from django.urls import path

from posts.methods.posts_methods import *
from posts.methods.post_likes_methods import *
from posts.methods.post_comments_methods import *


urlpatterns = [
    path("", posts_index, name="all_posts"),
    path("<int:id>", show_post, name="show_post"),
    path("user/<int:user_id>", user_posts, name="user_posts"),
    path("team/<int:team_id>", team_posts, name="team_posts"),
    path("feed", posts_feed, name="posts_feed"),


    path("create", posts_create, name="posts_create"),
    path("update/<int:id>", posts_update, name="posts_update"),
    path("delete/<int:id>", posts_delete, name="posts_delete"),

    path("likes/user/<int:user_id>", user_likes, name="get_user_likes"),
    path("likes/create/<int:id>", likes_create, name="like_post"),
    path("likes/remove/<int:id>", likes_remove, name="unlike_post"),

    path("comments/<int:post_id>",
         post_comments, name="get_post_comments"),
    path("comments/create/<int:post_id>",
         comments_create, name="create_comment"),
    path("comments/remove/<int:comment_id>",
         comments_remove, name="remove_comment"),
    path("comments/like/<int:comment_id>",
         comments_like, name="like_comment"),
    path("comments/unlike/<int:comment_id>",
         comments_unlike, name="unlike_comment"),
]
