from django.db import models


class Post(models.Model):
    id = models.AutoField(primary_key=True)
    text = models.TextField()
    media_url = models.URLField(blank=True, null=True)
    ENTITY_TYPES = [
        ('user', 'User'),
        ('team', 'Team'),
    ]
    entity_type = models.CharField(max_length=4, choices=ENTITY_TYPES)
    entity_id = models.IntegerField()
    created_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return f'Post:{self.id} - {self.text}'


class PostLike(models.Model):
    id = models.AutoField(primary_key=True)
    post = models.ForeignKey(Post, on_delete=models.CASCADE)
    user_id = models.IntegerField()
