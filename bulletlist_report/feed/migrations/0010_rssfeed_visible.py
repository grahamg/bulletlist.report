# Generated by Django 5.0.6 on 2024-07-10 02:10

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('feed', '0009_rssfeed_category'),
    ]

    operations = [
        migrations.AddField(
            model_name='rssfeed',
            name='visible',
            field=models.BooleanField(default=True),
        ),
    ]
