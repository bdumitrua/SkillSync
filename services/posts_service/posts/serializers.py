from rest_framework import serializers
from .models import Post, PostComment


class PostSerializer(serializers.ModelSerializer):
    likes_count = serializers.IntegerField(
        source='postlike_set.count', read_only=True)

    class Meta:
        model = Post
        fields = ['id', 'text', 'media_url',
                  'entity_type', 'entity_id', 'likes_count']


class PostCommentSerializer(serializers.ModelSerializer):
    likes_count = serializers.IntegerField(
        source='postcommentlike_set.count', read_only=True)

    class Meta:
        model = PostComment
        fields = ['id', 'user_id', 'text', 'media_url', 'likes_count']
