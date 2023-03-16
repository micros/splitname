DESCRIPCIÓN
===========
Esta librería le permite dividir nombres en español en sus cuatro componentes fundamentales:

* Primer nombre
* Otros nombres
* Primer apellido
* Otros apellidos

Adicionalmente la librería intenta detectar el género correspondiente al nombre (femenino o masculino).

La librería usa un diccionario propio de nombre y de reglas y está en capacidad de aprender nuevos términos con el uso, esto quiere decir que entre más se utilice el sistema tendrá mayor precisión.

En la actualidad el sistema no esta en capacidad de aprender nuevas reglas (solo nuevos términos) pero es una funcionalidad que se encuentra en desarrollo.

Tenga en cuenta que si utiliza esta librería en varios proyectos, con instalaciones independientes, no se beneficiará del aprendizaje automático pues las bases de datos serán diferentes. Ud puede exportar los nuevos nombres aprendidos entre las diferentes bases de datos de forma manual pero esto aún no está automatizado.

Se sugiere que ejecute el sistema varias veces sobre la misma colección de nombres para que pueda aprender nuevos nombres que le servirán para mejorar la precisión de la clasificación.

El sistema puede trabajar con nombres y apellidos desconocidos pero no ha sido probado con nombres extranjeros.

Por favor úselo bajo su propia responsabilidad. El licenciamiento le permite usarlo en cualquier proyecto pero siempre debe dar crédito al autor.

INSTALACIÓN
===========

    composer require micros/splitname

    php artisan migrate

USO
===

    use Micros\Splitname\SplitName;

    $splitter = new SplitName();

    $splitter->init();

    $tests = [
        'Leonardo Forero Sandoval',
        'Díaz Duran, Adriana',
        'José Francisco de San Martín y Matorras.',
        'Manuel José Joaquín del Corazón de Jesús Belgrano.',
        'Cornelio Judas Tadeo de Saavedra y Rodríguez.',
        'Bernardino de la Trinidad González de Rivadavia y Rodríguez de Rivadavia.',
        'Francisco José de Paula Santander Omaña',
        'Simón de la Santísima Trinidad Bolívar Ponte',
        'delcy gomez viuda de rodriguez',
        'Carmen Herrera García Vda. de López',
    ];

    foreach ($tests as $test) {
        dump($splitter->split($test)['classified']);
    }

    $splitter->process();


