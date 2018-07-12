<?php

namespace ProjectTwitter;

use Goteo\Application\EventListener\AbstractListener;

use Goteo\Application\AppEvents;
use Goteo\Application\Message;
use Goteo\Console\ConsoleEvents;

// We are going to use settings.yml values
use Goteo\Application\Config;
use TwitterAPIExchange;

use Goteo\Application\Event\FilterProjectEvent;
// we use the Console\EventFilterProjectEvent because is compatible with either the AppEvents::PROJECT_PUBLISH or the ConsoleEvents::PROJECT_PUBLISH. This way we can use the same function for both events
use Goteo\Console\Event\FilterProjectEvent as ConsoleProjectEvent;

class TwitterListener extends AbstractListener {
    public function onProjectPublished(ConsoleProjectEvent $event) {
        $project = $event->getProject();
        $domain = Config::getMainUrl();

        $name = trim($project->name ? $project->name : $project->id);
        // TODO: make this text configurable from settings
        $twit = "The new project \"$name\" has been published. Check it out here in $domain/project/{$project->id} #crowdfunding #opensource";

        $resource = 'https://api.twitter.com/1.1/statuses/update.json';
        // Get the auth settings from the config settings
        $settings = array(
            'oauth_access_token' => Config::get('plugins.project-twit.oauth_access_token'),
            'oauth_access_token_secret' => Config::get('plugins.project-twit.oauth_access_token_secret'),
            'consumer_key' => Config::get('plugins.project-twit.consumer_key'),
            'consumer_secret' => Config::get('plugins.project-twit.consumer_secret')
        );

        // publish the twit:
        $postfields = [
            'status' => $twit
        ];

        $twitter = new TwitterAPIExchange($settings);
        $result = json_decode($twitter->buildOauth($resource, 'POST')
            ->setPostfields($postfields)
            ->performRequest());

        // Add a flash Message if the event it's created in the website
        if($event instanceOf FilterProjectEvent) {
            if($result->errors) {
                $reason = $result->errors[0]->message;
                Message::error("Couldn't create a new twit: <strong>$reason</strong>");
            } else {
                $url = $result->entities->urls[0]->url;
                Message::info("A new twit has been created: <a href=\"$url\">$url</a>");
            }
        }
        // TODO: perform some logging or other operation depending on the twitter post operation

    }

    public static function getSubscribedEvents() {
        return array(
            AppEvents::PROJECT_PUBLISH => 'onProjectPublished',
            ConsoleEvents::PROJECT_PUBLISH => 'onProjectPublished'
        );
    }
}
