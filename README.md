<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [Laravel Resourceful](#laravel-resourceful)
  - [NOTE!](#note)
  - [Example](#example)
  - [Usage](#usage)
    - [Install through composer](#install-through-composer)
    - [Add Service Provider](#add-service-provider)
    - [Run it!](#run-it)
  - [Info](#info)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

# Laravel Resourceful 
Resourceful let's you create a full Resource withing seconds!
Use the artisan command and let it create a Migration, Seed, Request, Controller, Model and Views for your Resource!

##NOTE!
This is a first draft, feel free to create pull requests. Might have a lot of bugs so be careful with the usage!
Will continue working on it, tests are yet to come.

##Example
I would like to have a News Resource. I want to have all the CRUD functionality for it. So instead of creating all the Stuff by hand, i can use `php artisan make:resource news` to generate all the necessary stuff or just use single parts:

```bash
$> php artisan make:resource news
Model created successfully.
Created Migration: 2015_04_17_083658_create_news_table
Seed created successfully.
Request created successfully.
Controller created successfully.
Views created successfully.
```


##Usage
### Install through composer
`composer require laracasts/generators --dev``

### Add Service Provider
You probably don't want this on your production server, so instead of adding it to the `config/app.ch` we add it in `app/Providers/AppServiceProvider.php`, here's a example:

```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register('Remoblaser\Resourceful\ResourcefulServiceProvider');
    }
}
```

### Run it!
Now you can use the command. I've extracted everything in single commands so you're able to use the `make:controller:resourceful` command if you would like to create only the Controllers the resourceful way.
The `make:views` command is seperate too, so feel free to use this one aswell.

##Info
If you like my work, i would appreciate it if you would spread it! Thank you!
You can contact me through [Twitter](https://twitter.com/remoblaser)
