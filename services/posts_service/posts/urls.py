from django.urls import path

from posts.methods.posts_methods import *
from posts.methods.post_likes_methods import *

urlpatterns = [
    path("", posts_index, name="all_posts"),
    path("<int:id>", posts_show, name="show_post"),
    path("create", auth_posts_create, name="create_post"),
    path("update/<int:id>", auth_posts_update, name="update_post"),
    path("delete/<int:id>", auth_posts_delete, name="delete_post"),

    path("likes/create/<int:id>", auth_likes_create, name="like_post"),
    path("likes/remove/<int:id>", auth_likes_remove, name="unlike_post"),
]
