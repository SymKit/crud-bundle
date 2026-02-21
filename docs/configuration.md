# Configuration

The bundle can be configured under `symkit_crud` in your application config (e.g. `config/packages/symkit_crud.yaml`). All options are **enabled by default**; set a key to `false` to disable the corresponding services and features.

## Feature toggles

| Key | Default | Description |
|-----|--------|-------------|
| `crud.enabled` | `true` | CRUD persistence layer (provider, persistence manager, Doctrine handler). |
| `list.enabled` | `true` | List system (CrudList and CrudFilters Live components). |
| `form_types.slug` | `true` | SlugType and Slug Twig component. |
| `form_types.sitemap_priority` | `true` | SitemapPriorityType. |
| `form_types.icon_picker` | `true` | IconPickerType. |
| `form_types.active_inactive` | `true` | ActiveInactiveType. |
| `form_extensions.rich_select` | `true` | RichSelect form extension and component. |
| `form_extensions.password` | `true` | Password form extension and PasswordField component. |
| `form_extensions.translatable` | `true` | Translatable form extension and TranslatableField component. |
| `form_extensions.url` | `true` | URL form extension. |
| `form_extensions.form_section` | `true` | FormSection extension. |
| `form_extensions.dependency` | `true` | Dependency (dependent fields) extension. |
| `form_extensions.checkbox_rich_select` | `true` | CheckboxRichSelect extension. |
| `components.back_link` | `true` | BackLink Twig component. |
| `components.delete_form` | `true` | DeleteForm Twig component. |
| `components.slug` | `true` | Slug Live component. |
| `components.rich_select` | `true` | RichSelect Live component. |
| `components.password_field` | `true` | PasswordField Live component. |
| `components.translatable_field` | `true` | TranslatableField Live component. |
| `components.crud_list` | `true` | CrudList Live component. |
| `components.crud_filters` | `true` | CrudFilters Live component. |
| `twig_prepend` | `true` | Prepend Twig paths and component namespace. |
| `asset_mapper` | `true` | Register AssetMapper path for Stimulus controllers. |

## Example

Disable the list system and the slug form type:

```yaml
# config/packages/symkit_crud.yaml
symkit_crud:
  list:
    enabled: false
  form_types:
    slug: false
```

When a feature is disabled, its services and tags are not registered; your application will not load them.
