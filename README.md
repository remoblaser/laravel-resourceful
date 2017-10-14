[![Build Status](https://travis-ci.org/remoblaser/laravel-resourceful.svg?branch=master)](https://travis-ci.org/remoblaser/laravel-resourceful)


**Table of Contents**

- [Laravel Resourceful](#laravel-resourceful)
  - [NOTE!](#note)
  - [Usage](#usage)
      - [Install through composer](#install-through-composer)
      - [Add Service Provider](#add-service-provider)
      - [Run it!](#run-it)
  - [Example](#example)
    - [Generated Controller](#generated-controller)
    - [Generated Views](#generated-views)
  - [Info](#info)

# Laravel Resourceful 
Resourceful let's you create a full Resource withing seconds! Create a Resource with all the CRUD methods on every layer.
Use the artisan command and let it create a Migration, Seed, Request, Controller, Model and Views for your Resource!
**You're now able to exclude certain parts with --exclude. See the example!**

## NOTE!
This is a first draft, feel free to create pull requests. Might have a lot of bugs so be careful with the usage!
Will continue working on it, tests are yet to come.


## Usage
### Install through composer
`composer require remoblaser/resourceful --dev``

### Add Service Provider
You probably don't want this on your production server, so instead of adding it to the `config/app.ch` we add it in `app/Providers/AppServiceProvider.php`. here's a example:
Since we're using Jeffrey Way's / Laracats's Generators, we also need to register his ServiceProvider. Here's a example:

```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register('Remoblaser\Resourceful\ResourcefulServiceProvider');
        $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
    }
}
```

### Run it!
Now you can use the command. I've extracted everything in single commands so you're able to use the `make:resource:controller` command if you would like to create only the Controllers the resourceful way.
The `make:resource:views` command is seperate too, so feel free to use this one aswell.
With the `route:extend` command, you're able to extend your routes.php file with a resource controller.
The new command `route:bind` binds a route to your model. 
With the -b option, you can do this automatically when generating a resource.
If you want to see all the options, use `make:resource -h`.

## Example
I would like to have a News Resource. I want to have all the CRUD functionality for it. So instead of creating all the Stuff by hand, i can use `php artisan make:resource news` to generate all the necessary stuff or just use single parts:

```bash
$> php artisan make:resource news
Model created successfully.
Created Migration: 2015_04_17_083658_create_news_table
Seed created successfully.
Request created successfully.
Controller created successfully.
Views created successfully.
Routes successfully extended.
```

With the --exclude option you're able to exclude the Controller, Migration, Seed, Model and/or Views.
If you would like to generate everything except for the Views and the Seed, you can just use `--exclude=views,seed`.

You're able to submit a --commands option and choose which Actions/Commands you would like to have, commands need to be seperated with a ",".
The following commands are available: create, store, show, index, edit, update, destroy

### Generated Controller
```php
<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\News;
use App\Http\Requests\NewsRequest;

use Illuminate\Http\Request;

class NewsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	    $news = News::all();
		return view('news.index', compact('news'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('news.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  NewsRequest $request
	 * @return Response
	 */
	public function store(NewsRequest $request)
	{
		News::create($request->all());

		return redirect('/news');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$news = News::find($id);

		return view('news.show', compact('news'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$news = News::find($id);

        return view('news.edit', compact('news'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param  NewsRequest $request
	 * @return Response
	 */
	public function update(NewsRequest $request ,$id)
	{
        $news = News::find($id);
        $news->update($request->all());

        return redirect('/news');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$news = News::find($id);
		$news->destroy();

		return redirect('/news');
	}

}
````

### Generated Views
**Make sure to include [Illuminate/HTML](https://packagist.org/packages/illuminate/html), since the
Views are built with it.**

#### create.blade.php
```php
@extends('app')

@section('content')
    {!! Form::open(['route' => 'news.store'], 'method' => 'post']) !!}
        @include('news.partials.form', ['buttonText' => 'Create news'])
    {!! Form::close() !!}

    {{-- @include('errors.validation') --}}
@stop
```


#### edit.blade.php
```php
@extends('app')

@section('content')
    {!! Form::open(['route' => ['news.update', $news->id]], 'method' => 'post']) !!}
        @include('news.partials.form', ['buttonText' => 'Update news'])
    {!! Form::close() !!}

    {{-- @include('errors.validation') --}}
@stop
```

#### index.blade.php
```php
@extends('app')

@section('content')
    @foreach($newss as $news)
        {!! var_dump($news)) !!}
    @endforeach
@stop
```

#### show.blade.php
```php
@extends('app')

@section('content')
    {!! var_dump($news) !!}
@stop
```


## Info
If you like my work, i would appreciate it if you would spread it! Thank you!
You can contact me through [Twitter](https://twitter.com/remoblaser)
