# Installation

1. Add the bundle to your project:
   ```bash
   composer require symkit/crud-bundle
   ```

2. Register the bundle in `config/bundles.php`:
   ```php
   return [
       // ...
       Symkit\CrudBundle\CrudBundle::class => ['all' => true],
   ];
   ```

3. Add the controller to your `importmap.php`:
   ```php
   return [
       // ...
        'crud/rich-select_controller' => [
            'path' => 'crud/rich-select_controller.js',
        ],
        'crud/password-visibility_controller' => [
            'path' => 'crud/password-visibility_controller.js',
        ],
        'crud/slug_controller' => [
            'path' => 'crud/slug_controller.js',
        ],
        'crud/section-nav_controller' => [
            'path' => 'crud/section-nav_controller.js',
        ],
        'crud/dropdown_controller' => [
            'path' => 'crud/dropdown_controller.js',
        ],
        'crud/table-of-contents_controller' => [
            'path' => 'crud/table-of-contents_controller.js',
        ],
    ];
    ```

4. Update your `assets/stimulus_bootstrap.js` to register the controllers:
    ```javascript
    import RichSelectController from 'crud/rich-select_controller';
    app.register('rich-select', RichSelectController);

    import PasswordVisibilityController from 'crud/password-visibility_controller';
    app.register('password-visibility', PasswordVisibilityController);

    import SlugController from 'crud/slug_controller';
    app.register('crud--slug', SlugController);

    import UrlPreviewController from 'crud/url-preview_controller';
    app.register('crud--url-preview', UrlPreviewController);

    import SectionNavController from 'crud/section-nav_controller';
    app.register('crud--section-nav', SectionNavController);

    import DropdownController from 'crud/dropdown_controller';
    app.register('dropdown', DropdownController);

    import TableOfContentsController from 'crud/table-of-contents_controller';
    app.register('table-of-contents', TableOfContentsController);
    ```

5. Configure your Twig form theme in `config/packages/twig.yaml`:
   ```yaml
   twig:
       form_themes:
           - '@SymkitCrud/form/tailwind_layout.html.twig'
   ```

6. (Optional) Configure the bundle: all features are enabled by default. To disable some, see [Configuration](configuration.md).
