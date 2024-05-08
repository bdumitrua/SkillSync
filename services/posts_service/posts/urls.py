from django.urls import path

from posts.methods.posts_methods import *
from posts.methods.post_likes_methods import *
from posts.methods.post_comments_methods import *


urlpatterns = [
    path("", posts_index, name="all_posts"),
    path("<int:id>", posts_show, name="show_post"),
    path("create", auth_posts_create, name="create_post"),
    path("update/<int:id>", auth_posts_update, name="update_post"),
    path("delete/<int:id>", auth_posts_delete, name="delete_post"),

    path("likes/user/<int:user_id>", auth_user_likes, name="get_user_likes"),
    path("likes/create/<int:id>", auth_likes_create, name="like_post"),
    path("likes/remove/<int:id>", auth_likes_remove, name="unlike_post"),

    path("comments/<int:post_id>",
         auth_post_comments, name="get_post_comments"),
    path("comments/create/<int:post_id>",
         auth_comments_create, name="create_comment"),
    path("comments/remove/<int:comment_id>",
         auth_comments_remove, name="remove_comment"),
    path("comments/like/<int:comment_id>",
         auth_comments_like, name="like_comment"),
    path("comments/unlike/<int:comment_id>",
         auth_comments_unlike, name="unlike_comment"),
]
