<?php
# основной файл настроек приложения

return [

    'myapp' => [
        'name' => 'Интенсив Т4'
    ],
    
    'uploads' => [
        'news' => '/uploads/news'
    ],

    # подключение к БД
    'db' => [
        'default' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'dbname' => 'fw'
        ]
    ],

    # приложение будет использовать расширения
    'extensions' => [
        # встроенная поддержка фреймворка Bootstrap
        'bootstrap' => [
            # загружать расширение автоматически (можно не указывать)
            'autoload' => true,
            # использовать локальные файлы из /public/Assets или CDN
            'location' => 'local',
            # какую тему оформления Bootstrap использовать spacelab sandstone lumen
            'theme'    => 'sandstone'
        ]
    ]
    
];
