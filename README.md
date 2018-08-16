# Posts Grid (nmv-posts-grid)

A WordPress plugin that displays a lists of posts from a specific category.

## Usage

For now, the plugin only has a Shortcode. To use it, simply copy and paste the following shortcode to one of Your posts/pages/other:

```
[nmv_posts_grid category="The Category"]
```

Where "The Category" is the category You want to display posts from.

### Options

The shortcode includes the following options:

* category: The name (**not the slug**) of the category Your posts are in. __Default__: Eventos.
* quantity: How many posts to display. __Default__: 30.
* container\_class: A CSS class to add to the container. __Default__: Empty.
* display\_order: Should the posts be ordered in descending or ascending order? __Default__: ASC (the only other option being DESC').
* order\_by: Orders the posts by the specified custom fields value. __Default__: Empty. For now only two fields are recognized for the order:
  * nmv\_pg\_date: orders the posts by a valid date (format: YYYY/MM/DD).
  * nmv\_pg\_index: a numeric value.
* is\_gallery: a value set to true or false. If true, then each of the posts is expected to contain only images, without anything else (apart from the custom fields values). In this case, the posts are shown just as before, but instead of opening the post page, an image gallery overlay is shown.
* hide\_show\_more: hides the "show more" link at the bottom of each item.

#### Supported Custom Fields

The following custom fields are recognized:

* coming\_soon: set to __true__ or __1__ means that the post is not ready yet, but You still want to show something (coming soon event, for example). __Default__: not set.
* nmv\_pg\_url: if set, and the option __is_gallery is not set__ or set to __false__, then clicking the post will lead the user to this url instead of the post.
* nmv\_pg\_nogallery: if the option is\_gallery is set to true, but there are no images to display yet, then You can set this option to __true__ or __1__ (or anything, really) and the plugin will not load the gallery when the post is clicked. Once the gallery is ready, simply remove this value or set it to __0__ or __false__. __Default__: the plugin assumes the gallery is set.
* nmv\_pg\_caption: a subtitle that's displayed below the post title. __Default__: empty.

To add these values, go to Your dashboard -> Posts -> Select the post You want to show. Under the __Custom Fields__ section, click on Add New Value and enter the name and value You want.

If You cannot see the __Custom Fields__ section, then click on __Display Options__  (top left corner) and check the related option.

## Install

1. Download the plugin to Your computer.
2. Upload and unzip the plugin into Your WordPress plugins directory.
3. Go to the Dashboard -> Plugins and activate it.
4. Use it.

## Credits

* [WordPress](https://wordpress.org/), of course.
* [Wordpress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate).
* [PHP](https://php.net/), what would we be without it...
* [Slick JS](https://kenwheeler.github.io/slick/).

## Thanks

Thanks to anyone that takes the time to test this plugin and provides Me any feedback.

## Contact

You can get in touch through [My Website](https://nicomv.com/).
