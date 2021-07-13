# Firebase For Laravel

Link: https://github.com/kreait/laravel-firebase

## Installation

The above package requires Laravel 6.x and higher.
```
composer require kreait/laravel-firebase
```

Add the following service provider in `config/app.php`
```
<?php

return [
    // ...
    'providers' => [
        // ...
        Kreait\Laravel\Firebase\ServiceProvider::class
    ]
    // ...
];
```

## Configuration

1. Generate a Service Account in your [Firebase](https://firebase.google.com/) project if you haven't done it yet
2. Download the Service Account JSON file
3. Specify environment variable starting with `FIREBASE_` in `.env` file. For example:
```
# relative or full path to the Service Account JSON file
FIREBASE_CREDENTIALS=
# You can find the database URL for your project at
# https://console.firebase.google.com/project/_/database
FIREBASE_DATABASE_URL=https://<your-project>.firebaseio.com

```
4. Run the following command for further configuration in `config/firebase.php`
```
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider" --tag=config
```

Read more here: https://github.com/kreait/laravel-firebase
