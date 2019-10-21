# [Diagramo](http://diagramo.com/) Laravel App
Step to install

Step 1: Install [Laravel](https://laravel.com)

Step 2: composer.json add the following line
```bash
"autoload": {
    "psr-4": {
            "App\\": "app/",
            "prasanth\\diagramo\\":"packages/prasanth/diagramo/src"
        },
        "classmap": [
        "database/seeds",
        "database/factories"
    ]
},
```

Step 3:
```bash
composer update --no-scripts
composer dump-autoload
```
Step 4: Add the following line in */config/app.php*

```bash
'providers' => [
    /*
    * Laravel Framework Service Providers...
    */
    Illuminate\Auth\AuthServiceProvider::class,
    //.. Other providers
    prasanth\diagramo\DiagramoServiceProvider::class,
],
```
Step 4: got to browser and try to access the URL as following
```bash
http://localhost/project_name/public/diagramo/index
```



#### Note: 
we use [Diagramo](http://diagramo.com/) app to create this package


