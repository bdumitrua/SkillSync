from django import forms


def validate_entity_type(value):
    if value not in ['team', 'user']:
        raise forms.ValidationError("Entity type must be 'team' or 'user'")


class UpdatePostForm(forms.Form):
    text = forms.CharField(max_length=255)
    media_url = forms.CharField(required=False)


class CreatePostForm(forms.Form):
    text = forms.CharField(max_length=255)
    media_url = forms.CharField(required=False)
    entity_type = forms.CharField(validators=[validate_entity_type])
    entity_id = forms.IntegerField(min_value=1)
