# Posts Grid (nmv-posts-grid)

[English](README.md)

Un plugin para wordpress que muestra una lista de posts de una categoría específica.

## Instalación

1. Descarga el plugin a tu computador.
2. Sube el archivo a tu hosting y descomprime el archivo en la carpeta de plugins de WordPress.
3. Ve al Escritorio -> Plugins, busca el plugin Posts Grid y actívalo.

## Uso

Por ahora, el plugin sólo consta de un Shortcode. Para usarlo, simplemente copia y pega el siguiente shortcode en una de tus publicaciones, páginas u otro tipo de post:

```
[nmv_posts_grid category="Una Categoria"]
```

Donde "Una Categoria" es el título (no el slug ni el id) de la categoría que contiene los posts que mostrarás.

### Opciones

El shortcode soporta los siguientes atributos:

* category: El nombre (**no el slug**) de la categoría que contiene tus publicaciones. __Defecto__: Eventos.
* quantity: Limita la cantidad de posts que se mostrarán. __Defecto__: 30.
* container\_class: Una clase CSS que se añade al contenedor de los posts. __Defecto__: Vacío.
* display\_order: Orden en el que se muestran las publicaciones (ASC, ascendente o DESC, descendente). __Defecto__: ASC.
* order\_by: Ordena las publicaciones en base al valor de un campo personalizado. __Defecto__: Vacío. Por ahora sólo dos campos se reconocen:
  * nmv\_pg\_date: ordena los posts en base a una fecha específica (formato: AAAA/MM/DD).
  * nmv\_pg\_index: un valor numeríco. Los posts se ordenarán en base a este valor y el atributo __display\_order__
* is\_gallery: un valor que acepta true o false. Si es true, entonces se espera que cada publicación contenga sólo imágenes, sin ningún otro texto o etiquetas HTML. En este caso, las publicaciones son mostradas con el mismo formato de grilla, pero al hacer clic se cargará un slide con las imágenes en lugar de abrir la página de la publicación.
* hide\_show\_more: oculta el botón de "Mostrar Más" al final de cada publicación.

#### Campos Personalizados Soportados

The following custom fields are recognized:
El plugin soporta los siguientes campos personalizados:

* coming\_soon: si se asigna el valor __true__ o __1__ significa que la publicación no está lista aún, pero aún así quieres mostrar algo (como un "evento próximo"). __Defecto__: not set.
* nmv\_pg\_url: si se asigna un valor y el atributo __is_gallery__ no existe o tiene el valor __false__, al hacer clic en el post el usuario será redirigido a esta url en lugar de la url de la publicación. Si la url de la publicación es /categoria-1/lorem-ipsum, y se define el campo personalizado como https://nicomv.com/, entonces el usuario irá a nicomv.com en lugar de /categoria-1/lorem-ipsum.
* nmv\_pg\_nogallery: si el atributo __is\_gallery__ tiene el valor true, pero no hay imágenes aún (porque no las has subido, por ejemplo), entonces puedes asignar el valor __true__ o __1__ (o cualquier otra cosa en realidad) y el plugin no cargará la galería cuando se haga clic sobre esta publicación. Cuando la galería esté lista, simplemente quita este campo personalizado o cambia el valor a __0__ o __false__. __Defecto__: el plugin asume que la galería existe.
* nmv\_pg\_caption: un sub-título que se muestra debajo del título de la publicación. __Defecto__: vacío.

Para añadir estos valores, ve al Escritorio de Wordpress -> Publicaciones -> Selecciona la publicación que quieres modificar. En la sección de __Campos Personalizados__, haz clic sobre __Añadir Nuevo Campo__ e ingresa el nombre y valor que quieres.

Si no puedes ver la sección __Campos Personalizados__ haz clic sobre __Opciones de Pantalla__ (esquina superior derecha) y marca la casilla que corresponde.

## Créditos

* [WordPress](https://wordpress.org/), of course.
* [Wordpress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate).
* [PHP](https://php.net/), what would we be without it...
* [Slick JS](https://kenwheeler.github.io/slick/).

## Agradecimientos

Gracias a cualquier que se tome el tiempo de probar este plugin.

## Contacto

Puedes ponerte en contacto conmigo a través de [Mi Sitio Web](https://nicomv.com/).
