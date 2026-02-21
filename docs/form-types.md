# Form Types Reference

## Simple ChoiceType

Standard select fields are automatically transformed into `RichSelect`:

```php
$builder->add('category', ChoiceType::class, [
    'choices' => [
        'Development' => 'dev',
        'Design' => 'design',
    ],
]);
```

## IconPickerType

A ready-to-use form type for selecting Heroicons with a searchable interface and previews:

```php
use Symkit\CrudBundle\Form\Type\IconPickerType;

$builder->add('icon', IconPickerType::class, [
    'label' => 'Choose an Icon',
    'required' => false,
]);
```

## ActiveInactiveType

A standardized status field with "Active" and "Inactive" labels and matching icons:

```php
use Symkit\CrudBundle\Form\Type\ActiveInactiveType;

$builder->add('isActive', ActiveInactiveType::class);
```

## SitemapPriorityType

A specialized dropdown for sitemap priority selection (0.1 to 1.0) with visual indicators:

```php
use Symkit\CrudBundle\Form\Type\SitemapPriorityType;

$builder->add('priority', SitemapPriorityType::class);
```

## Advanced RichSelect

Customize search, icons, and clear behavior:

```php
$builder->add('status', ChoiceType::class, [
    'choices' => [
        'Active' => 'active',
        'Inactive' => 'inactive',
    ],
    'placeholder' => 'Select a status...',
    'searchable' => true, 
    'required' => false, // Displays a clear button (x)
    'choice_icons' => [
        'active' => 'heroicons:check-circle-20-solid', // Simple string
        'inactive' => [                              // Advanced array with custom color
            'name' => 'heroicons:x-circle-20-solid',
            'class' => 'text-red-500', 
        ],
    ],
]);
```

## Checkbox Transformation

All `CheckboxType` fields are automatically transformed into `RichSelect` toggles with Yes/No options:

```php
$builder->add('is_public', CheckboxType::class, [
    'label' => 'Make public?',
]);
```
This renders a dropdown with ✅ **Yes** and ❌ **No**.

## Premium Password Component

Standard `PasswordType` fields are automatically enhanced with a visibility toggle and a real-time, decorative checklist indicating password strength rules.

```php
$builder->add('password', PasswordType::class, [
    'show_strength' => true,
    'min_length' => 12,
    'require_uppercase' => true,
    'require_numbers' => true,
    'require_special' => true,
]);
```

**Options:**
- `show_strength`: (bool) Show/hide the strength bar (default: `true`).
- `min_length`: (int) Minimum required length (default: `8`).
- `require_uppercase`: (bool) Check for capital letters (default: `false`).
- `require_numbers`: (bool) Check for digits (default: `false`).
- `require_special`: (bool) Check for symbols (default: `false`).

## Multi-language (i18n) Support

Automatically transform `TextType` and `TextareaType` into premium tabbed translatable fields. It works out-of-the-box by detecting your application's global locales.

```php
$builder->add('title', TextType::class, [
    'translatable' => true,
]);
```

**Options:**
- `translatable`: (bool) Enable multi-language mode (default: `true` for all `TextType`/`TextareaType` once the extension is active).
- `locales`: (array) List of locales to display as tabs. If omitted, it automatically uses `%kernel.enabled_locales%` and prioritizes `%kernel.default_locale%`.

## URL Field with Preview

Enhanced `UrlType` that allows users to quickly open the link in a new tab.

```php
$builder->add('website', UrlType::class, [
    'link_button' => true,
]);
```

**Options:**
- `link_button`: (bool) Add an external link icon inside the input (default: `false`). The icon only appears when the user types a value.
- `default_protocol`: (string) Standard Symfony option, useful to ensure valid links (e.g. `https`).

## Slug with Auto-Sync

A smart `SlugType` that automatically generates a slug from another field (e.g., Name) and allows manual overrides with a lock system.

```php
use Symkit\CrudBundle\Form\Type\SlugType;

$builder
    ->add('name', TextType::class, [
        'label' => 'Name',
        'help' => 'Internal name for this menu (e.g. Primary Navigation)',
    ])
    ->add('code', SlugType::class, [
        'label' => 'Code',
        'required' => false,
        'target' => 'name',              // Syncs with the 'name' field
        'unique' => true,                // Check uniqueness in DB
        'entity_class' => Menu::class,   // Entity to check against
        'slug_field' => 'code',          // DB column to check (default: 'slug')
        'help' => 'Unique code used to retrieve this menu.',
    ])
;
```

**Options:**
- `target`: (string) Name of the field to listen to (e.g., `'title'`).
- `locked`: (bool) Initial locked state (default: `true`).
- `unique`: (bool) Check for uniqueness in the database (default: `false`).
- `entity_class`: (string) Class name for uniqueness check (required if `unique` is `true`).
- `slug_field`: (string) Database field to check (default: `'slug'`).
- `repository_method`: (string) Custom repository method to call for uniqueness check. Signature: `method(string $slug, mixed $entityId): string`.
- `auto_update`: (bool) Whether the slug should auto-update by default (default: `true`, but automatically becomes `false` if the field has an initial value in edit mode to prevent accidental overwrites).

### How it Works

1.  **Auto-Sync**: When locked, the component listens to `input` events on the `target` field.
2.  **Smart Locking**: On edit pages, it starts **unlocked** if you've already manually edited it, or **locked** but with sync disabled until you explicitly interact with the lock.
3.  **Uniqueness**: It automatically appends suffixes (e.g., `-1`, `-2`) if a conflict is detected in the database, even during manual edits.
4.  **Backend Resilience**: It performs a final sync/validation on the backend to ensure data integrity even if the frontend events are slightly delayed.

### Custom Repository Method Example

```php
// In your Repository
public function findUniqueSlug(string $slug, ?int $entityId): string
{
    // Custom logic (e.g. scoping by tenant)
    // ...
    return $uniqueSlug;
}
```
