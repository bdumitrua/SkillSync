from rest_framework import serializers
from .models import Post


class PostSerializer(serializers.ModelSerializer):
    likes_count = serializers.IntegerField(
        source='postlike_set.count', read_only=True)

    class Meta:
        model = Post
        fields = ['id', 'text', 'media_url',
                  'entity_type', 'entity_id', 'likes_count']
