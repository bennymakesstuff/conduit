# Conduit - Finch Motor Company

Conduit is an application that provides connectivity between Finch resources and applications.

For initial rollout it will provide connectivity in the form of syncing between Dolibarr and Quickbooks Time.

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
#### Quickbooks Time Sync

- Sync Users
- Sync Time Clockings (Packets)
- Sync Projects
- Sync Tasks (Workorders)
- Sync Project / User Mapping



### Setup


### Tasks
All tasks are prepended with "php artisan" when running from console
#### Laravel Tasks
- ***cache:clear*** - Clears the laravel cache for clearing .env and route issues

#### Dolibarr Tasks
- ***dolibarr:sync-users*** - Syncs users from Dolibarr to Conduit
- ***dolibarr:sync-projects*** - Syncs users from Dolibarr to projects
- ***dolibarr:sync-workorders*** - Syncs users from Dolibarr to workorders
- ***dolibarr:check-connection*** - Checks the status of the connected Dolibarr database

#### Quickbooks Time Tasks
- ***quickbooks:sync-users*** - Syncs users from Conduit to Quickbooks
- ***quickbooks:sync-projects*** - Syncs projects from Conduit to Quickbooks

#### Conduit Tasks
- ***conduit:check-connection*** - Checks the status of the Conduit database
- ***conduit:check-email*** - Fires of a Test Email
- ***conduit:check-discord*** - Fires of a Test Discord Notification to the logger channel
- ***conduit:check-discord-error*** - Fires of a Test Discord Notification to the error channel

### Bugs


### Todo
