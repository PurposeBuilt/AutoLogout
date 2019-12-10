# Auto-Logout Plugin.

Auto-Logout plugin for OctoberCMS, This plugin gives you the ability to automatically log-out users when they leave the site entirely not just closing one tab.

## Main Scenarios

  - 1 tab open, close tab, should be logged out.
 - Any number of tabs open to same page, close one tab, should still be logged in, close all tabs, should be logged out.
 - Open the site from a browser (Chrome), Open the same page from another browser (Safari), Close all tabs on Chrome, you should be still logged in unless you also close all tabs on Safari.
 - Any number of tabs open, cut internet connection, should be logged out.
 - Any number of tabs open, reboot machine (system crash), should be logged out.
 - User should be logged out after being inactive for X amount of mins regardless the number of tabs or browsers open.

## Main Features
- Supports Backend Users.
- Supports Frontend Users.
- Can enable/disable Backend/Frontend Functionality.
- Can set a time out of inactivity allowed for users before getting logged out.
- [Extendable](#extending) for more authentication facades other than Backend and `Rainlab.User`.

## Installation
- Via OctoberCMS marketplace.
- Via Backend Admin Panel.
	- Settings -> System -> Updates & Plugins -> Install Plugins -> Search for `Auto-logout`

## Prerequisites
You need to make sure your server has the followings installed and accessible from the command line:
- NodeJS.
- NPM.

## Getting Started
First step to use the plugin after installing it from OctoberCMS marketplace is to set it up. We've created a command to make it a lot simpler for you! All you need is to run this command from the project root!
```
php artisan logout:setup
```
This command will use `NPM` to install all the dependencies we need to get started.

## Run it!
The plugin basically runs a `nodejs` server behind the scenes. To start running the plugin you'll need to run the following command:
```
php artisan logout:run
```
And this command will need to be running behind the scenes, So you'll need to use a service like `screen` or `supervisor` on linux to do so. And we would recommend using `supervisor`. Please check out [Using Supervisor Section](#using-supervisor).

#### A few things to notice!
- The default port that will be used is `3000`
- If you want to change the port you can do so from the backend admin panel, Make sure you do that before running the plugin server. And whenever you change that port number you should restart the plugin server.
- Make sure the port number you're using is open. To know more about firewalls on Ubuntu you can follow [this link](https://www.digitalocean.com/community/tutorials/how-to-setup-a-firewall-with-ufw-on-an-ubuntu-and-debian-cloud-server).

## Plugin Settings
![Plugin Settings](https://d1sz9tkli0lfjq.cloudfront.net/items/0d1l1h1M0g2p3j0q2p1u/Image%202019-12-10%20at%204.13.23%20PM.png?v=677a9ee0)

In the plugin settings in backend admin panel you have essentially 3 settings:
* Port Number.
	* That's the Port Number that will be used in the `nodejs` server used by the plugin. That port should be open in the firewall of the server so the plugin can function properly.
	* Whenever this Port Number is changed, It's a MUST to restart the `logout:run` command to be effective.
* Enable Backend Auto-Logout.
	* If this checkbox is checked, the `Admins Mins of inactivity` will be shown.
	* If this checkbox is checked, the `Backend Popup Custom Class` will be shown.
	* If this checkbox is checked, the plugin will log out backend users if they leave the backend panel.
* `Admins Mins of inactivity`.
	* Plus the auto-logout when the user leaves the site, you may specify the number of minutes allowed for the user to be inactive on the site before getting kicked out! Keep it `0` if you don't want this functionality.
* `Backend Popup Custom Class`.
	* If you want to customize the backend warning popup, you can add as many CSS classes as you need in this field.

Additionally, If `Rainlab.User` plugin is installed you'll see the following fields:
* Enable Frontend Auto-Logout.
	* If this checkbox is checked, the `Users Mins of inactivity` will be shown.
	* If this checkbox is checked, the `Frontend Popup Custom Class` will be shown.
	* If this checkbox is checked, the plugin will log out frontend users if they leave the frontend site.
* `Users Mins of inactivity`.
	* Plus the auto-logout when the user leaves the site, you may specify the number of minutes allowed for the user to be inactive on the site before getting kicked out! Keep it `0` if you don't want this functionality.
* `Frontend Popup Custom Class`.
	* If you want to customize the frontend warning popup, you can add as many CSS classes as you need in this field.


## Using Supervisor
It's super easy to run Supervisor to make sure the plugin always works behind the scenes even if the server reboots anytime. To install it on linux you just need to run the following command:
```
sudo apt-get install supervisor
```
Supervisor configuration files are typically stored in the `/etc/supervisor/conf.d` directory. Within this directory, you may create any number of configuration files that instruct supervisor how your processes should be monitored. For example, let's create a `logout-worker.conf` file that starts and monitors a `logout:run` process:
```php
[program:logout-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/forge/app.com/artisan logout:run
autostart=true
autorestart=true
user=forge
numprocs=8
redirect_stderr=true
stdout_logfile=/home/forge/app.com/worker.log
```

## Extending

The plugin is using (The Builder/Manager) design pattern using a class that comes with `Illuminate\Support` package by default in Laravel 5. You can check out the documentation of that class from [this link](https://laravel.com/api/5.6/Illuminate/Support/Manager.html). The main class that should be extended is `PBS\Logout\Processor`.

```php
Processor::extend('writer', function($app)
{
    // Writer class should implement PBS\Logout\Contracts\Driver
    return new Writer($app);
});
```

It's important to notice that any new driver should implement `PBS\Logout\Contracts\Driver` interface.

## Contribution Guide

Feel free to open a pull request for any fix or feature you'd like to add. Please keep in mind the following before opening your pull request.

- We follow the  [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)  coding standard and the  [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)  autoloading standard. Your code should follow the same standards.
- You should document every single method like the Laravel community.

```php
/**
 * Register a binding with the container.
 *
 * @param  string|array  $abstract
 * @param  \Closure|string|null  $concrete
 * @param  bool  $shared
 * @return void
 *
 * @throws \Exception
 */
public function bind($abstract, $concrete = null, $shared = false)
{
    //
}
```
Please note that in Laravel `@param` should be followed by two spaces, but we're not strict about that rule. You can write two or one space. It's up to you!


- Icon By: [Mahmoud Essam](https://www.behance.net/jamaiqa)
