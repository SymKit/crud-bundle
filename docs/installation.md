# Installation

1. Add the bundle to your project:
   ```bash
   composer require symkit/crud-bundle
   ```

2. Register the bundle in `config/bundles.php`:
   ```php
   return [
       // ...
       Symkit\CrudBundleBundle::class => ['all' => true],
       Symkit\FormBundle\FormBundle::class => ['all' => true], # Required dependency
   ];
   ```

3. (Optional) Configure the bundle: all features are enabled by default. To disable some, see [Configuration](configuration.md).

For form types (RichSelect, Slug, etc.) and the Tailwind form theme, see the [Symkit Form Bundle](file:///wsl.localhost/Ubuntu/home/seb/current/packages/form-bundle/README.md) documentation.
