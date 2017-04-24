LaraMod Admin Blog 0.1 Alpha
----------------------------
LaraMod is a modular Laravel based CMS.
https://github.com/LaraModulus

Installation
---------------
```
composer require LaraModulus\admin-blog
```
 **config/app.php**
 
```php 
'providers' => [
    ...
    LaraMod\AdminBlog\AdminBlogServiceProvider::class,
]
```
**Publish migrations**
```
php artisan vendor:publish --tag="migrations"
```
**Run migrations**
```
php artisan migrate
```

In `config/admincore.php` you can edit admin menu

**DEMO:** http://laramod.novaspace.eu/admin
```
user: admin@admin.com
pass: admin
```