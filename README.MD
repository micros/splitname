

DESCRIPTION
===========
This library for Laravel 10 and up, allows you to split Spanish names into four main parts:

* First name
* Middle name
* Surname
* Other surnames

In addition, this library tries to identify the gender (female or male)

This library uses its internal term dictionary and its own rules definition. It is capable to learn new terms by its use, which means the more is used the more accurate is the library.

The system cannot learn new rules (only new terms) but this is a feature under development.

Note that if the library is used in many projects, with independent installations, you will get no benefits from the learning capabilities since are different databases. You should sync manually the newly learned terms between databases.

Is advisable to run the system many times against the database to will be clasified in order to let the system to learn new terms which improve the precision.

The system can handle unknow names and surnames but has no being tested with non-Spanish names.

Please use under your own risk and read carefully the licence.


DESCRIPCIÓN
===========
Esta librería para Laravel 10 y superior, le permite dividir nombres en español en sus cuatro componentes fundamentales:

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

INSTALLATION / INSTALACIÓN
========================

    composer require micros/splitname

    php artisan migrate

HOW TO USE / COMO USAR
======================

    use Micros\Splitname\SplitName;

    $splitter = new SplitName();

    $tests = [
        'Leonardo Forero Sandoval',
        'Díaz Duran, Adriana',
        'José Francisco de San Martín y Matorras.',
        'Manuel José Joaquín del Corazón de Jesús Belgrano.',
        'Cornelio Judas Tadeo de Saavedra y Rodríguez.',
        'Bernardino de la Trinidad González de Rivadavia y Rodríguez de Rivadavia.',
        'Francisco José de Paula Santander Omaña',
        'Simón José Antonio de la Santísima Trinidad Bolívar Ponte y Palacios Blanco',
        'delcy gomez viuda de rodriguez',
        'Carmen Herrera García Vda. de López',
    ];

    foreach ($tests as $test) {
        $parts = $splitter->split($test)['classified'];
        dump($parts);
    }

    $splitter->process();



