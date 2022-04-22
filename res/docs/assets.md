
## Assets

Place static assets required for build inside the [`assets`](./../../assets) directory.

Reference images & other assets from the `assets` directory in Twig templates
using `asset()`:

```twig
{{ asset(article.thumbnail) }}
```

## Contents' images

Place content's images inside the [`content/images`](./../../content/images) directory.

### Resize images

You can resize an image using a preset defined in `config/packages/glide.yaml`:

```twig
{{ article.thumbnail|glide_image_preset('article_thumbnail') }}
```

or with arbitrary options:

```twig
{{ article.thumbnail|glide_image_resize({
    w: 80,
    h: 60,
}) }}
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
