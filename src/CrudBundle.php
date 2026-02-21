<?php

declare(strict_types=1);

namespace Symkit\CrudBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symkit\CrudBundle\Contract\CrudListProviderInterface;
use Symkit\CrudBundle\Contract\CrudPersistenceHandlerInterface;
use Symkit\CrudBundle\Contract\CrudPersistenceManagerInterface;
use Symkit\CrudBundle\Crud\Component\CrudFilters;
use Symkit\CrudBundle\Crud\Component\CrudList;
use Symkit\CrudBundle\Crud\Persistence\Handler\DoctrinePersistenceHandler;
use Symkit\CrudBundle\Crud\Persistence\Manager\CrudPersistenceManager;
use Symkit\CrudBundle\Crud\Persistence\Provider\CrudListProvider;
use Symkit\CrudBundle\Form\Extension\CheckboxRichSelectExtension;
use Symkit\CrudBundle\Form\Extension\DependencyExtension;
use Symkit\CrudBundle\Form\Extension\FormSectionExtension;
use Symkit\CrudBundle\Form\Extension\PasswordExtension;
use Symkit\CrudBundle\Form\Extension\RichSelectExtension;
use Symkit\CrudBundle\Form\Extension\TranslatableExtension;
use Symkit\CrudBundle\Form\Extension\UrlExtension;
use Symkit\CrudBundle\Form\Type\ActiveInactiveType;
use Symkit\CrudBundle\Form\Type\IconPickerType;
use Symkit\CrudBundle\Form\Type\SitemapPriorityType;
use Symkit\CrudBundle\Form\Type\SlugType;
use Symkit\CrudBundle\Service\HeroiconProvider;
use Symkit\CrudBundle\Twig\Component\BackLink;
use Symkit\CrudBundle\Twig\Component\DeleteForm;
use Symkit\CrudBundle\Twig\Component\PasswordField;
use Symkit\CrudBundle\Twig\Component\RichSelect;
use Symkit\CrudBundle\Twig\Component\Slug;
use Symkit\CrudBundle\Twig\Component\TranslatableField;

final class CrudBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('crud')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('list')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('form_types')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('slug')->defaultTrue()->end()
                        ->booleanNode('sitemap_priority')->defaultTrue()->end()
                        ->booleanNode('icon_picker')->defaultTrue()->end()
                        ->booleanNode('active_inactive')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('form_extensions')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('rich_select')->defaultTrue()->end()
                        ->booleanNode('password')->defaultTrue()->end()
                        ->booleanNode('translatable')->defaultTrue()->end()
                        ->booleanNode('url')->defaultTrue()->end()
                        ->booleanNode('form_section')->defaultTrue()->end()
                        ->booleanNode('dependency')->defaultTrue()->end()
                        ->booleanNode('checkbox_rich_select')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('components')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('back_link')->defaultTrue()->end()
                        ->booleanNode('delete_form')->defaultTrue()->end()
                        ->booleanNode('slug')->defaultTrue()->end()
                        ->booleanNode('rich_select')->defaultTrue()->end()
                        ->booleanNode('password_field')->defaultTrue()->end()
                        ->booleanNode('translatable_field')->defaultTrue()->end()
                        ->booleanNode('crud_list')->defaultTrue()->end()
                        ->booleanNode('crud_filters')->defaultTrue()->end()
                    ->end()
                ->end()
                ->booleanNode('twig_prepend')->defaultTrue()->end()
                ->booleanNode('asset_mapper')->defaultTrue()->end()
            ->end();
    }

    /**
     * @param array{crud: array{enabled: bool}, list: array{enabled: bool}, form_types: array{slug: bool, sitemap_priority: bool, icon_picker: bool, active_inactive: bool}, form_extensions: array{rich_select: bool, password: bool, translatable: bool, url: bool, form_section: bool, dependency: bool, checkbox_rich_select: bool}, components: array{back_link: bool, delete_form: bool, slug: bool, rich_select: bool, password_field: bool, translatable_field: bool, crud_list: bool, crud_filters: bool}, twig_prepend: bool, asset_mapper: bool} $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services();
        $crud = $config['crud']['enabled'];
        $list = $config['list']['enabled'];
        $formTypes = $config['form_types'];
        $formExt = $config['form_extensions'];
        $components = $config['components'];

        if ($crud) {
            $services->set(CrudListProvider::class)->autowire();
            $services->alias(CrudListProviderInterface::class, CrudListProvider::class);
            $services->set(DoctrinePersistenceHandler::class)->autowire();
            $services->alias(CrudPersistenceHandlerInterface::class, DoctrinePersistenceHandler::class);
            $services->set(CrudPersistenceManager::class)->autowire();
            $services->alias(CrudPersistenceManagerInterface::class, CrudPersistenceManager::class);
        }

        $services->set(HeroiconProvider::class);

        if ($formTypes['active_inactive']) {
            $services->set(ActiveInactiveType::class)->tag('form.type');
        }
        if ($formTypes['sitemap_priority']) {
            $services->set(SitemapPriorityType::class)->tag('form.type');
        }
        if ($formTypes['icon_picker']) {
            $services->set(IconPickerType::class)->autowire()->autoconfigure()->tag('form.type');
        }

        if ($formExt['checkbox_rich_select']) {
            $services->set(CheckboxRichSelectExtension::class)->tag('form.type_extension');
        }
        if ($formExt['password']) {
            $services->set(PasswordExtension::class)->tag('form.type_extension');
        }
        if ($formExt['rich_select']) {
            $services->set(RichSelectExtension::class)->tag('form.type_extension');
        }

        if ($components['rich_select']) {
            $services->set(RichSelect::class)->autowire()->autoconfigure()
                ->tag('twig.component', ['key' => 'RichSelect'])
                ->tag('ux.live_component');
        }
        if ($components['password_field']) {
            $services->set(PasswordField::class)->autowire()->autoconfigure()
                ->tag('twig.component', ['key' => 'PasswordField'])
                ->tag('ux.live_component');
        }
        if ($components['translatable_field']) {
            $services->set(TranslatableField::class)->autowire()->autoconfigure()
                ->tag('twig.component', ['key' => 'TranslatableField'])
                ->tag('ux.live_component');
        }

        if ($formExt['translatable']) {
            $defaultLocale = $builder->getParameter('kernel.default_locale');
            $enabledLocales = $builder->hasParameter('kernel.enabled_locales')
                ? $builder->getParameter('kernel.enabled_locales')
                : [$defaultLocale];
            $services->set(TranslatableExtension::class)
                ->arg(0, $defaultLocale)
                ->arg(1, $enabledLocales)
                ->tag('form.type_extension');
        }

        if ($formExt['url']) {
            $services->set(UrlExtension::class)->tag('form.type_extension');
        }
        if ($formExt['dependency']) {
            $services->set(DependencyExtension::class)->tag('form.type_extension');
        }
        if ($formExt['form_section']) {
            $services->set(FormSectionExtension::class)->tag('form.type_extension');
        }

        if ($formTypes['slug']) {
            $services->set(SlugType::class)->tag('form.type');
        }
        if ($components['slug']) {
            $services->set(Slug::class)->autowire()->autoconfigure()
                ->tag('twig.component', ['key' => 'Slug'])
                ->tag('ux.live_component');
        }

        if ($components['back_link']) {
            $services->set(BackLink::class)->autoconfigure()->tag('twig.component', ['key' => 'BackLink']);
        }
        if ($components['delete_form']) {
            $services->set(DeleteForm::class)->autoconfigure()->tag('twig.component', ['key' => 'DeleteForm']);
        }

        if ($list) {
            $services->set(CrudFilters::class)->autowire()->autoconfigure()
                ->tag('twig.component', ['key' => 'SymkitCrud:CrudFilters'])
                ->tag('ux.live_component');
            $services->set(CrudList::class)->autowire()->autoconfigure()
                ->tag('twig.component', ['key' => 'SymkitCrud:CrudList'])
                ->tag('ux.live_component');
        }
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $path = $this->getPath();
        $configs = $builder->getExtensionConfig('symkit_crud');
        $merged = array_merge(...array_values($configs));
        $twigPrepend = $merged['twig_prepend'] ?? true;
        $assetMapper = $merged['asset_mapper'] ?? true;

        if ($twigPrepend) {
            $container->extension('twig', [
                'paths' => [
                    $path.'/templates' => 'SymkitCrud',
                ],
            ], true);
            $container->extension('twig_component', [
                'defaults' => [
                    'Symkit\CrudBundle\Twig\Component\\' => '@SymkitCrud/components/',
                ],
            ], true);
        }

        if ($assetMapper) {
            $container->extension('framework', [
                'asset_mapper' => [
                    'paths' => [
                        $path.'/assets/controllers' => 'crud',
                    ],
                ],
            ], true);
        }
    }
}
