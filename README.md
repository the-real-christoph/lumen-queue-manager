# Lumen Queue Manager [work in progress]

Basic queue manager for database queues used in lumen framework.

## Features

* Listing pending jobs and status
* Detailed job view
* Removal of pending jobs.
* Filtering by database queue name

![Preview](https://raw.githubusercontent.com/the-real-christoph/lumen-queue-manager/master/preview.png)


## Planned features (coming soon)
* failed queue + retry feature
* improved UI
* any more ideas?

## Installation

Add repository to your composer.json file (packagist.org submit will be done later)

```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/the-real-christoph/lumen-queue-manager"
        }
    ],
```

```
composer require the-real-christoph/lumen-queue-manager
```

Make sure this two lines are present / activated in bootstrap/app.php

```
$app->withFacades();

$app->withEloquent();
```

Add service provider in bootstrap/app.php

```
$app->register(LumenQueueManager\Providers\QueueManagerServiceProvider::class);
```

## Publish assets to your project (for configs and/or JavaScript files later)

You can utilize a tool like this to use vendor:publish commands:
https://github.com/laravelista/lumen-vendor-publish

```
php artisan vendor:publish --tag=lumen-queue-manager
```

## License

The Lumen Queue Manager is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

