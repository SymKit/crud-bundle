# Symkit CRUD Bundle

Symfony bundle for modern applications: generic CRUD, Live lists (filters, sort, pagination), form types and extensions, Twig components (RichSelect, Slug, Password, Translatable, BackLink, DeleteForm, CrudList, CrudFilters), Tailwind theme and Stimulus.

Part of [SymKit](https://github.com/symkit). For SEO and breadcrumbs, use [symkit/metadata-bundle](https://packagist.org/packages/symkit/metadata-bundle) with `AbstractCrudController`.

## Features

- **Generic CRUD**: event-driven persistence layer, CQRS-lite.
- **Live lists**: CrudList and CrudFilters (filters, sort, pagination).
- **Form types**: Slug, SitemapPriority, IconPicker, ActiveInactive, FormSection.
- **Form extensions**: RichSelect, Password, Translatable, Url, Dependency, CheckboxRichSelect.
- **Twig components**: BackLink, DeleteForm, RichSelect, PasswordField, TranslatableField, Slug, CrudList, CrudFilters.
- **Sectioned forms**: card-based layout with sticky navigation.
- **Tailwind theme**: dark mode support.

## Documentation

1. [**Installation**](docs/installation.md)
2. [**Configuration**](docs/configuration.md) — enable/disable features
3. [**Form types**](docs/form-types.md)
4. [**Sectioned forms**](docs/sectioned-forms.md)
5. [**CRUD system**](docs/crud-system.md)
6. [**List system**](docs/list-system.md)
7. [**Utility components**](docs/utility-components.md)
8. [**Dependent fields**](docs/dependent-fields.md)

## Contributing

- Install Git hooks (optional, to strip `Co-authored-by:` from commit messages): `make install-hooks`
- Quality: `make quality` (cs-check, phpstan, deptrac, tests)
- Run before committing: `make cs-fix`, `make phpstan`, `make test`
