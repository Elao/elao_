# elao_

The Elao website.

## Prerequisite

Either:

- Docker
- or a local Node 16+, PHP 8.1+ & Symfony CLI install

## Setup

If you want to use the Docker stack, setup the project using:

    make up

Then, log into the container using

    make sh

And install the dependencies with

    make install

(for a local installation, simply run `make install`).

## Contributing

When Docker, use
    
    make sh

to log into the container before running the next commands.

If you use a local install, next commands can be used, but you can also start
the whole application using a single command:

    make start

## Start the server

    make up

The site is now available at: http://localhost:8000

Alternatively, if you use a local install & Symfony CLI, you can use:

    make serve

The Symfony CLI exposes you the URL at which the site is available.

## Build assets

    make build

### Start Webpack dev-server

To build assets for development purposes, with HMR (hot reloading) enabled, run:

    make dev

## Writing an article

Please, follow the [guidelines](https://elao.github.io/elao_/blog/styleguide/example/).

## Assets

Reference images & other assets in Twig templates using `asset()`:

```twig
{{ asset(article.thumbnail) }}
```

### Resize images

You can resize an image using a preset defined in `config/packages/glide.yaml`:

```twig
{{ asset(article.thumbnail|glide_image_preset('article_thumbnail')) }}
```

or with arbitrary options:

```twig
{{ asset(article.thumbnail|glide_image_resize({
    w: 80,
    h: 60,
})) }}
```

See [Glide's documentation](https://glide.thephpleague.com/1.0/api/quick-reference/) for available options.

In order to automatically generate images for retina screens (dpr x2), you can either:

- use the `backgroundImageSrcset` macro in Twig for background images:
  
    ```twig
    {% import "macros.html.twig" as macros %}
    
    <div class="article-banner__cover" style="{{ macros.backgroundImageSrcset(article.thumbnail, 'article_banner') }}"></div>
    ```

- or the `imageSrcset` macro in Twig for `<img />` tags:
  
    ```twig
    {% import "macros.html.twig" as macros %}
  
    <img {{ macros.imageSrcset(article.thumbnail, 'article_banner') }} />
    ```
