# Dependent Fields (DependencyExtension)

The `DependencyExtension` provides a way to group form fields and allow users to toggle between them using a premium UI switcher. This is useful for mutually exclusive fields, such as "URL" vs "Internal Route".

## Features

- **Icon + Label Switcher**: A modern UI rendered above field labels to switch between grouped fields.
- **Auto-Cleanup**: When a field is hidden via the switcher, its value is automatically cleared in the browser to ensure clean data submission and valid backend logic.
- **WYSWYG Support**: Works seamlessly with regular inputs and custom components like `RichSelect`.

## Form Options

All Symfony form types gain the following options via the extension:

| Option | Type | Description |
| :--- | :--- | :--- |
| `dependency_group` | `string` | The unique name of the group. Sibling fields with the same group name will be toggled. |
| `dependency_label` | `string` | The label to display in the switcher button. Defaults to the field's `label`. |
| `dependency_icon` | `string` | An optional Heroicon name (e.g., `heroicons:globe-alt-20-solid`) to display in the switcher. |

## Usage Example

In your `FormType`, simply group fields by using the `dependency_group` option:

```php
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sedie\RoutingBundle\Entity\Route;

// ...

$builder
    ->add('urlTo', TextType::class, [
        'label' => 'Destination URL',
        'dependency_group' => 'target',
        'dependency_label' => 'URL',
        'dependency_icon' => 'heroicons:globe-alt-20-solid',
        'required' => false,
    ])
    ->add('route', EntityType::class, [
        'class' => Route::class,
        'label' => 'Internal Route',
        'dependency_group' => 'target',
        'dependency_label' => 'Route',
        'dependency_icon' => 'heroicons:link-20-solid',
        'required' => false,
    ]);
```

## How it Works

1. **PHP Extension**: `DependencyExtension` collects metadata about grouped fields and determines which one is "active" based on existing data (or defaults to the first one).
2. **Twig Layout**: The `form_row` block in `tailwind_layout.html.twig` detects the `dependency_group`. It applies a `hidden` class to inactive fields and renders the switcher buttons.
3. **Stimulus Controller**: The `crud--dependency` controller handles the `click` event on switcher buttons. It toggles visibility and triggers `input`/`change` events after clearing values to keep everything in sync (including Live Components).

> [!IMPORTANT]
> To ensure backend validation (like "either URL or Route must be set") works correctly, the system clears the value of hidden fields before submission.
