from django import forms


def validate_entity_type(value):
    if value not in ['team', 'user']:
        raise forms.ValidationError("Entity type must be 'team' or 'user'")


class CreatePostForm(forms.Form):
    text = forms.CharField(required=True, max_length=255)
    media_url = forms.CharField(required=False)
    entity_type = forms.CharField(
        required=True, validators=[validate_entity_type])
    entity_id = forms.IntegerField(required=True, min_value=1)


class UpdatePostForm(forms.Form):
    text = forms.CharField(required=True, max_length=255)
    media_url = forms.CharField(required=False)


class CreatePostCommentForm(forms.Form):
    text = forms.CharField(required=True, max_length=200)
    media_url = forms.CharField(required=False)
