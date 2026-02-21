# Symkit CRUD Bundle

Symfony bundle for modern applications: generic CRUD, Live lists (filters, sort, pagination), Twig components (BackLink, DeleteForm, CrudList, CrudFilters) and Stimulus.

Part of [SymKit](https://github.com/symkit). For form types, extensions, and Tailwind form theme, use [symkit/form-bundle](https://packagist.org/packages/symkit/form-bundle). For SEO and breadcrumbs, use [symkit/metadata-bundle](https://packagist.org/packages/symkit/metadata-bundle) with `AbstractCrudController`.

## Features

- **Generic CRUD**: event-driven persistence layer, CQRS-lite.
- **Live lists**: CrudList and CrudFilters (filters, sort, pagination).
- **Twig components**: BackLink, DeleteForm, CrudList, CrudFilters.

## Documentation

1. [**Installation**](docs/installation.md)
2. [**Configuration**](docs/configuration.md) — enable/disable features
3. [**CRUD system**](docs/crud-system.md)
4. [**List system**](docs/list-system.md)
5. [**Utility components**](docs/utility-components.md)

## Contributing

- Install Git hooks (optional, to strip `Co-authored-by:` from commit messages): `make install-hooks`
- Quality: `make quality` (cs-check, phpstan, deptrac, tests)
- Run before committing: `make cs-fix`, `make phpstan`, `make test`
