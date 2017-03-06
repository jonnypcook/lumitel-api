## Database Configuration and Tools
Please see below for cheat sheet on Eloquent models, seeding, migrations and factories.
Also contains information about Rinvex repository setup.

### Eloquent Models
The Eloquent models allow us to map database tables into Laravels ORM.
To create an Eloquent model through artisan: 
```
php artisan make:model ModelName
```
In the case of this project we currently put all models in the _Models_ directory using the following command:
```
php artisan make:model Models/ModelName
```
See: https://laravel.com/docs/5.4/eloquent

### Migrations
Migrations are used to construct database objects as a project develops. They build on top 
of each other can be rolled-back incrementally. We can create a new migration by using:
```
php artisan make:migration create_table_name_table --create=table_name__
```
This will create a new migration file in database/migrations folder.

To combine a migration file with the creation of the Eloquent model file then use:
```
php artisan make:model Device --migration
```
This will create a migration file alongside the /app/<modelName>.php Eloquent ServiceProvider.
<br><br>
Examples of usage for this projects structure:
```
php artisan make:model Models/DeviceLiteIpStatus --migration
```
To run created migrations or to rollback the last migration:
```
php artisan migrate
php artisan migrate:rollback
```
See: https://laravel.com/docs/5.4/migrations

### Seeds
Seeds allow us to seed the database objects.  Typically we will use this to insert test values
or value models (e.g. DeviceType).
```
php artisan make:seeder NameSeeder
```
To run a seeder use:
```
php artisan db:seed --class=DatabaseLiveSeeder
```
See: https://laravel.com/docs/5.4/seeding

### Factories
Factories can be used in conjunction with Seeders to provide more complex seeding capabilities to the application.
Factories should be created in the __database/factories__ directory using the form: __ModelFactory.php__

See: https://laravel.com/docs/5.4/seeding#using-model-factories


### Rinvex Repositories
Rinvex repositories build on the Laravel Repository pattern to provide (controlled) cached model data.
When creating these repositories we need to ensure that the following steps are taken:
* Create a _repository_ for the Model in __Repositories__ directory.
* Create a _repository contract_ for the Model in __Repositories__ directory.
* Set the default caching model for the Repository in __ModelRepository__ file
* Add mapping in __RinvexContractProvider__ in the __Providers__ directory: 
```
$this->app->bind(ModelRepositoryContract::class, ModelRepository::class);
```
See: 
* https://github.com/rinvex/repository/wiki
* https://github.com/rinvex/repository/wiki/Advanced
* https://blog.rinvex.com/the-art-of-using-repositories-in-laravel-5-3-ee20a1eacb35#.l6hlcp2ju


