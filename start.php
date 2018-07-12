<?php


use Goteo\Application\App;
use Goteo\Application\Config;
use Symfony\Component\DependencyInjection\Reference;

// Autoload additional Classes in this plugin
Config::addAutoloadDir(__DIR__ );

// Include our custom composer vendor file
Config::addComposerAutoload(__DIR__  . '/vendor/autoload.php');

// Get the service container to add our custom services in it:
$sc = App::getServiceContainer();

// Register a custom reference with our EventListener class
$sc->register('project-twit.twitter_listener', 'ProjectTwitter\TwitterListener')
   ->setArguments(array(new Reference('logger'))); // 'logger' is the default logger defined in the main container.php file, because our event listener inherits from the standard AbstractListener used in Goteo that needs a Logger class as argument's constructor


// Add the subscriber to the service container
$sc->getDefinition('dispatcher')
   ->addMethodCall('addSubscriber', array(new Reference('project-twit.twitter_listener')))
;
