### Laravel Further Studies:
# File Storage

## Configuration:
All Configuration are in ```config/filesystems.php```. \
in this directory, you can define as many disks you want on the **local driver** <br>
> note that every disk is related to the **root** directory that you can define in the filesystems.php file.
 
next, you need to create a symbolic link which is made by php artisan command: ```php artisan storage:link ``` \
now, you can create a URL to the files like this:
```php
echo asset('storage/file.txt');
```
to configure more links you can use this:
```php
'links' => [
public_path('storage') => storage_path('app/public'),
public_path('images') => storage_path('app/images'),
],
```
## How Would The File Representation look like in the Database?
the files would be URLs to the real files.
* Directories are represented as paths so you can retrieve all the files in a specific directory.
 