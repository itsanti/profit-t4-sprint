## «T4: быстрый вход в мир современных фреймворков»
[страница интенсива](http://pr-of-it.ru/sprints/12.html)
### Д3 №1
1.4 `Layouts\Index.html` это родительский шаблон для экшнов, таких как `Templates\Index\Default.html`

1.6 **работа с расширениями в T4**

При обработке запроса создается объект приложения и вызывается метод `run()`,
который инициализирует расширения (Extensions).

Создается объект класса `T4\Core\Std $app->extensions`, который содержит в себе все используемые расширения.
Описать их можно с помощью ключа `extensions` и вложенных массивов с конфигурацией для каждого расширения в файле конфигурации приложения.

Например, для расширения `foo` получим следующий конфиг:
```
return [
  'extensions' => [
    # имя расширения
    'foo' => [ # конфигурация расширения
      # нестандартное имя класса для расширения
      'class' => 'Bar',
      # загружать расширение автоматически (можно не указывать)
      'autoload' => true
    ]
  ]
];
```
если для расширения не задан `class`, то поиск класса для загрузки будет осуществляться по стандартным `NS`:

1. `\App\Extensions\Foo\Extension` расширения, которые определяет приложение

2. `\T4\Mvc\Extensions\Foo\Extension` или встроенные расширения T4

Далее создается объект расширения одного из перечисленных классов и ссылка на него
записывается в `$app->extensions->foo`.

Если в настройке расширения не указан параметр  `'autoload' => false`, то будет
вызван метод `init()` класса расширения. В `init()` каждое расширение может использовать
свои дополнительные параметры конфигурации.

Пример подключения фреймворка Bootstrap и его параметры можно посмотреть в `/protected/config.php`.