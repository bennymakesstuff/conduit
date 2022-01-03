# Conduit Server

Conduit is an application that acts an an API.

### Technologies
Conduit uses the Laravel framework for the application.
This was chosen for the following reasons:
- Robust production grade framework
- Huge community of developers and long term support from Laravel
- PHP which is a familiar technology within Finch
- Open Source and Free
- One of the largest utilised frameworks in the world
- Eloquent ORM abstracts away the need for complex SQL and allows for migrations

### Features


### Setup
***Run Server***
```php
php artisan serve
```

### Tasks
All tasks are prepended with "php artisan" when running from console
#### Laravel Tasks
- ***cache:clear*** - Clears the laravel cache for clearing .env and route issues

#### Conduit Tasks
- ***conduit:check-connection*** - Checks the status of the Conduit database
- ***conduit:check-email*** - Fires of a Test Email
- ***conduit:check-discord*** - Fires of a Test Discord Notification to the logger channel
- ***conduit:check-discord-error*** - Fires of a Test Discord Notification to the error channel

### Bugs


### Todo
