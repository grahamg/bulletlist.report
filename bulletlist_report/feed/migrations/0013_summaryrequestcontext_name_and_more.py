# Generated by Django 5.0.6 on 2024-07-26 01:08

import django.db.models.deletion
from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('feed', '0012_rssfeeditemsummaryrequest_queued_by_and_more'),
    ]

    operations = [
        migrations.AddField(
            model_name='summaryrequestcontext',
            name='name',
            field=models.CharField(default='wizkab', help_text='Not used with API provider call, a unique name identifier used to differentiate against multiple context choices.', max_length=255, unique=True),
        ),
        migrations.AlterField(
            model_name='rssfeed',
            name='summary_request_context',
            field=models.ForeignKey(blank=True, help_text='Optional. Specifies API parameters to the chosen provider. To disable summaries for a given feed source, leave the request context field blank.', null=True, on_delete=django.db.models.deletion.CASCADE, to='feed.summaryrequestcontext'),
        ),
        migrations.AlterField(
            model_name='summaryrequestcontext',
            name='api_provider',
            field=models.CharField(choices=[('chatgpt', 'OpenAI ChatGPT'), ('gemini', 'Google Gemini')], default='chatgpt', help_text='Specifies which API provider to use for summary requests.', max_length=10),
        ),
        migrations.AlterField(
            model_name='summaryrequestcontext',
            name='engine',
            field=models.CharField(blank=True, help_text='Optional. If nothing is specified here, "gpt-4" will be used.', max_length=10, null=True),
        ),
        migrations.AlterField(
            model_name='summaryrequestcontext',
            name='max_tokens',
            field=models.IntegerField(blank=True, help_text='Optional. If nothing is specified here, the quantity 150 will be used.', null=True),
        ),
        migrations.AlterField(
            model_name='summaryrequestcontext',
            name='prompt_template',
            field=models.TextField(help_text='Use { feed_item_url } template tag as placeholder for article to summarize.'),
        ),
    ]
