# Generated by Django 5.0.6 on 2024-07-07 18:20

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('feed', '0007_alter_rssfeeditem_unique_together_and_more'),
    ]

    operations = [
        migrations.CreateModel(
            name='RSSFeedCategory',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('name', models.CharField(max_length=255, unique=True)),
            ],
        ),
    ]
