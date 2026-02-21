# Sectioned Forms (Card Layout)

Create modern forms with card-based sections, sticky navigation, and responsive design using the `inherit_data` pattern.

## 1. Define groups in your FormType

```php
use Symfony\Component\Form\Extension\Core\Type\FormType;

class MyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Group: General Information
        $builder->add(
            $builder->create('general', FormType::class, [
                'inherit_data' => true,
                'label' => 'General Information',
                'section_icon' => 'heroicons:information-circle-20-solid',
                'section_description' => 'Basic details about your item.',
            ])
                ->add('name', TextType::class, ['label' => 'Name'])
                ->add('email', TextType::class, ['label' => 'Email'])
        );

        // Group: Settings
        $builder->add(
            $builder->create('settings', FormType::class, [
                'inherit_data' => true,
                'label' => 'Settings',
                'section_icon' => 'heroicons:cog-6-tooth-20-solid',
                'section_description' => 'Configure behavior and options.',
            ])
                ->add('isActive', CheckboxType::class)
                ->add('priority', IntegerType::class)
        );
    }
}
```

## 2. Use the sectioned form template

```twig
{% embed '@SymkitCrud/form/sectioned_form.html.twig' with {form: form} %}
    {% block form_actions %}
        <button type="submit" form="delete-form"
            class="inline-flex items-center gap-2 py-2.5 px-4 rounded-lg text-red-600 text-sm font-medium hover:bg-red-50 transition-all cursor-pointer">
            {{ ux_icon('heroicons:trash-20-solid', {class: 'w-4 h-4'}) }}
            Delete
        </button>
        <button type="submit" form="{{ form.vars.id }}"
            class="inline-flex items-center gap-2 py-2.5 px-4 rounded-lg bg-slate-900 text-white text-sm font-medium shadow-lg hover:bg-slate-800 transition-all cursor-pointer">
            {{ ux_icon('heroicons:check-20-solid', {class: 'w-4 h-4'}) }}
            Save
        </button>
    {% endblock %}
{% endembed %}
```

### Features

- **Sticky Navigation**: Shows on desktop (left sidebar), hidden on mobile
- **Card-based Sections**: Each group renders as a card with header and fields
- **Split Layout**: Description on left, fields on right (desktop)
- **Responsive**: Stacks on mobile, side-by-side on desktop
- **Dark Mode**: Full support
- **Smooth Scrolling**: Click navigation to scroll to sections

### Group Options

| Option | Type | Description |
|--------|------|-------------|
| `inherit_data` | bool | **Required.** Must be `true` to pass data to parent form. |
| `label` | string | Section title displayed in nav and card header. |
| `section_icon` | string | Heroicon name for the section. |
| `section_description` | string | Description text shown in the card header. |

### Available Blocks

| Block | Description |
|-------|-------------|
| `form_actions` | Override the sticky footer buttons (use `{{ parent() }}` to keep default save button). |
| `extra_nav_items` | Add custom links to the navigation sidebar. |

---

## Entity Form Template

A complete CRUD form template that combines BackLink, DeleteForm, and sectioned_form. **Render directly from your controller** - no custom template needed for standard CRUD operations!

### Basic Usage (from Controller)

```php
// Create action - no delete button
return $this->render('@SymkitCrud/crud/entity_form.html.twig', [
    'form' => $form->createView(),
    'back_route' => 'admin_list',
    'back_label' => 'Back to list',
    'page_title' => 'Create Item',
    'page_description' => 'Add a new item.',
]);

// Edit action - with delete button
return $this->renderEdit($entity, $request, [
    'page_title' => 'Edit: ' . $entity->getName(),
    'page_description' => 'Update item details.',
    'template_vars' => [
        'show_back' => false,   // Hide back button
        'show_delete' => false, // Hide delete button and form
    ],
]);
```

### With Custom Content (embed)

For pages that need extra content (like a related items list), use embed with blocks:

```twig
{% extends 'admin/layout/base.html.twig' %}

{% block body %}
    {% embed '@SymkitCrud/crud/entity_form.html.twig' with {
        form: form,
        back_route: 'admin_menu_list',
        back_label: 'Back to list',
        show_delete: true,
        delete_route: 'admin_menu_delete',
        delete_route_params: {id: menu.id},
        entity_id: menu.id,
        page_title: menu.name,
        page_description: 'Manage menu settings.',
    } %}
        {% block extra_nav_items %}
            <li>
                <a href="#related-items" 
                   data-section-id="related-items"
                   data-crud--section-nav-target="navLink"
                   data-action="click->crud--section-nav#scrollTo"
                   class="flex items-center gap-2 px-4 py-3 border-l-2 border-transparent text-slate-600 hover:bg-slate-50 transition-colors">
                    {{ ux_icon('heroicons:list-bullet-20-solid', {class: 'w-4 h-4'}) }}
                    Related Items
                </a>
            </li>
        {% endblock %}
        
        {% block after_form %}
            <section id="related-items" class="scroll-mt-28" data-crud--section-nav-target="section">
                {# Your custom content here #}
            </section>
        {% endblock %}
    {% endembed %}
{% endblock %}
```

### Template Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `form` | FormView | *required* | The Symfony form to render |
| `base_layout` | string | *required* | The layout template to extend |
| `back_route` | string | *required* | Route name for the back link |
| `back_route_params` | array | `{}` | Route parameters for back link |
| `back_label` | string | `'Back'` | Label for back link |
| `show_back` | bool | `true` | Show/hide the back button |
| `page_title` | string | `''` | Page title (H1) |
| `page_description` | string | `''` | Page description |
| `show_delete` | bool | `false` | Show delete button |
| `delete_route` | string | - | Route for delete action |
| `delete_route_params` | array | `{}` | Route params for delete |
| `entity_id` | mixed | - | Entity ID for CSRF token |
| `delete_confirm_message` | string | `'Are you sure...'` | Delete confirmation text |
| `delete_button_label` | string | `'Supprimer'` | Delete button text |

### Available Blocks (for embed)

| Block | Description |
|-------|-------------|
| `entity_form_extra_nav_items` | Add nav links (outputs inside `<ul>`) |
| `after_form` | Content after the form (e.g. related items list) |
