# Auto Login for Laravel

The AutoLogin package for Laravel enables seamless autologin functionality via temporary links.
Empower your users with convenient access to your application by securely logging them in using time-limited, temporary autologin links.


## Installation

You can install Memento in your project using the Composer package manager:

```bash
composer require esplora/auto-login
```

## Usage

To get started with the AutoLogin package, follow the steps below:

1. Add the route for autologin in your `routes/web.php` file:

```php
use Esplora\AutoLogin\AutoLogin;

AutoLogin::routes();
```

This will register the necessary route for autologin functionality.

2. Create temporary autologin links in your controllers, emails, or other relevant places using the following methods:

```php
AutoLogin::to('/path');
// or use named routes
AutoLogin::route('telescope', [
    'active' => '1'
]);
```

These methods will generate temporary links that automatically log in the user. By default, the current authenticated user will be used. However, if the code is executed in the console or in a non-authenticated context, you need to pass the user ID as the second argument:

```php
AutoLogin::to('/path', 123);
// or use named routes
AutoLogin::route('telescope', [
    'active' => '1'
], 123);
```

## Configuration

The AutoLogin package provides some configuration options to suit your needs. By default, the autologin URL is set to `/autologin`. However, if you have changed this route usage, you can specify the desired URL using the following method:

```php
use Esplora\AutoLogin\AutoLogin;

AutoLogin::routes('/your/path/autologin');
```

Additionally, you can specify a Blade template to customize the autologin view. Simply pass the desired template path as the second argument:

```php
use Esplora\AutoLogin\AutoLogin;

AutoLogin::routes('/your/path/autologin', 'path.for.your.blade.template');
```

In your custom template, you can access the `link` variable, which contains the autologin URL, and use it as needed.

```html
<!-- path.for.your.blade.template -->

<script>
  setTimeout(function(){
    window.location.href = "{{ $link }}";
  }, 3000);
</script>
```


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
