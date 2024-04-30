from django.urls import path

from . import methods

urlpatterns = [
    path("", methods.index, name="all_posts"),
    path("create", methods.create, name="create_post"),
    path("update/<int:id>", methods.update, name="update_post"),
    path("delete/<int:id>", methods.delete, name="delete_post"),
]
