# Diagramo Laravel App
Step to install
Step 1: Install laravel

Step 2: composer.json add the following line

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

