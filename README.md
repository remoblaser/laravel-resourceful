<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [Laravel Resourceful](#laravel-resourceful)
  - [NOTE!](#note)
  - [Example](#example)
    - [Generated Controller](#generated-controller)
    - [Generated Views](#generated-views)
  - [Usage](#usage)
    - [Install through composer](#install-through-composer)
    - [Add Service Provider](#add-service-provider)
    - [Run it!](#run-it)
  - [Info](#info)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

# Laravel Resourceful 
Resourceful let's you create a full Resource withing seconds! Create a Resource with all the CRUD methods on every layer.
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

###Generated Controller
```php
<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\News;

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
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();

		News::create($input);

		return view('news.store');
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
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
        $news = News::find($id);
        $news->update($input);

        return view('news.update');
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

		return view('news.destroy');
	}

}
````

###Generated Views

####create.blade.php
```php
@extends('app')

@section('content')
    {!! Form::open(['route' => 'news.store'], 'method' => 'post']) !!}
        @include('news.partials.form', ['buttonText' => 'Create news'])
    {!! Form::close() !!}

    {{-- @include('errors.validation') --}}
@stop
```

####destroy.blade.php
```php
@extends('app')

@section('content')
    <h2>Deleted.</h2>
    <p>{!! link_to_route('news.index', 'View all') !!}
@stop
```

####edit.blade.php
```php
@extends('app')

@section('content')
    {!! Form::open(['route' => ['news.update', $news->id]], 'method' => 'post']) !!}
        @include('news.partials.form', ['buttonText' => 'Update news'])
    {!! Form::close() !!}

    {{-- @include('errors.validation') --}}
@stop
```

####index.blade.php
```php
@extends('app')

@section('content')
    @foreach($newss as $news)
        {!! var_dump($news)) !!}
    @endforeach
@stop
```

####show.blade.php
```php
@extends('app')

@section('content')
    {!! var_dump($news) !!}
@stop
```

####store.blade.php
```php
@extends('app')

@section('content')
    <h2>Create.</h2>
    <p>{!! link_to_route('news.index', 'View all') !!}
@stop
```

####update.blade.php
```php
@extends('app')

@section('content')
    <h2>Updated.</h2>
    <p>{!! link_to_route('news.index', 'View all') !!}
@stop
```


##Usage
### Install through composer
`composer require remoblaser/resourceful --dev``

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
