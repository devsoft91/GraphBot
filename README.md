# GraphBot
A Telegram PHP bot that returns statistics on traffic messages in a group chat in the last 24 hours

**In this mini-guide you will _NOT_ learn on how to create a Telegram Bot or configure a webhook**

### Download jpgraph libraries
Download jpgraph libraries and add them to your project in order to allow graphBot.php to create the graph image.

### Set-up graphBot.php
After downloaded the project, change in graphBot.php the settings in order to make it able to connect to your relational database.
You can also customize your generated graph layout, dimensions, line colors, ecc...

### Load mysql procedure into phpMyAdmin
Import source.sql into phpMyAdmin.
It allows to count messagges incoming from chats and to create chat 24-hour tables for groups when the bot is added to a group chat.

### Schedule crontab job
Edit remove.php in accordance with your database settings and schedule a crontab job in order to allow it be executed every hour.
It keep database tables updated with current (server) time in order to allow you see always past 24 hour statistics.

**Main encountered problems consists in a jpgraph library bug (find solution on the web) and on settings ownering of files and folder in order to allow the executions of automated cronjob and bot tasks**
