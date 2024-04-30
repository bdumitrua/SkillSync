from django.urls import path

from . import methods

urlpatterns = [
    path("", methods.index, name="all_posts"),
    path("<int:id>", methods.show, name="show_post"),
    path("create", methods.auth_create, name="create_post"),
    path("update/<int:id>", methods.auth_update, name="update_post"),
    path("delete/<int:id>", methods.auth_delete, name="delete_post"),
]
