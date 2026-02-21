# Symkit CRUD Bundle

Bundle Symfony pour applications modernes : CRUD générique, listes Live (filtres, tri, pagination), types et extensions de formulaire, composants Twig (RichSelect, Slug, Password, Translatable, BackLink, DeleteForm, CrudList, CrudFilters), thème Tailwind et Stimulus.

Fait partie de [SymKit](https://github.com/symkit). Pour le SEO et les breadcrumbs, utilisez [symkit/metadata-bundle](https://packagist.org/packages/symkit/metadata-bundle) avec `AbstractCrudController`.

## Fonctionnalités

- **CRUD générique** : couche de persistance événementielle, CQRS-lite.
- **Listes Live** : CrudList et CrudFilters (filtres, tri, pagination).
- **Types de formulaire** : Slug, SitemapPriority, IconPicker, ActiveInactive.
- **Extensions** : RichSelect, Password, Translatable, Url, FormSection, champs dépendants, CheckboxRichSelect.
- **Composants Twig** : BackLink, DeleteForm, RichSelect, PasswordField, TranslatableField, Slug, CrudList, CrudFilters.
- **Formulaires sectionnés** : mise en page par cartes avec navigation sticky.
- **Thème Tailwind** : support dark mode.

## Documentation

1. [**Installation**](docs/installation.md)
2. [**Configuration**](docs/configuration.md) — activer/désactiver les fonctionnalités
3. [**Types de formulaire**](docs/form-types.md)
4. [**Formulaires sectionnés**](docs/sectioned-forms.md)
5. [**Système CRUD**](docs/crud-system.md)
6. [**Système de listes**](docs/list-system.md)
7. [**Composants utilitaires**](docs/utility-components.md)
8. [**Champs dépendants**](docs/dependent-fields.md)

## Contribuer

- Installer les hooks Git (optionnel, pour retirer les `Co-authored-by:` des messages de commit) : `make install-hooks`
- Qualité : `make quality` (cs-check, phpstan, deptrac, tests)
- Tout doit passer avant un commit : `make cs-fix`, `make phpstan`, `make test`
