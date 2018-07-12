# goteo-project-twitter-plugin

This is a plugin for the Goteo [OpenSource Crowdfunding Platform](https://goteo.org).

This is the code of an example in the [documentation](http://goteofoundation.github.io/goteo/docs/developers/events.html#plugin) of the [Goteo Source Code](https://github.com/goteofoundation/goteo).

## What does it do?

It creates a twit every time a project is published in a Goteo installation.

## Configuration

Download the source code of this plugin in the `extend/` folder of goteo, for example:

```bash
cd your-goteo-path/extend
git clone https://github.com/microstudi/goteo-project-twitter-plugin.git
```

Install the required dependencies:

```bash
cd your-goteo-path/extend/goteo-project-twitter-plugin
composer install
```

Now go to https://apps.twitter.com, create a new custom app, grab the Consumer Key and Secret, generate an Access Token and Secret.

Then add in your `config/settings.yml`, in the section "plugins":

```
plugins:
    goteo-project-twitter-plugin:
        active: true
        oauth_access_token: YOUR_OAUTH_ACCESS_TOKEN
        oauth_access_token_secret: YOUR_OAUTH_ACCESS_TOKEN_SECRET
        consumer_key: YOUR_CONSUMER_KEY
        consumer_secret: YOUR_CONSUMER_SECRET


```

That's it, every time a project is published, a twit will be created in the account where the Twitter app is created.

@Author
Ivan Vergés

@License
AGPL 3.0
