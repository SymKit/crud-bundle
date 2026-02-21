<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Crud\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatableMessage;
use Symkit\CrudBundle\Contract\CrudPersistenceManagerInterface;
use Symkit\CrudBundle\Crud\Controller\AbstractCrudController;
use Symkit\MetadataBundle\Contract\PageContextBuilderInterface;

final class AbstractCrudControllerTest extends TestCase
{
    private CrudPersistenceManagerInterface $persistenceManager;
    private PageContextBuilderInterface $pageContextBuilder;

    protected function setUp(): void
    {
        $this->persistenceManager = $this->createMock(CrudPersistenceManagerInterface::class);
        $this->pageContextBuilder = $this->createMock(PageContextBuilderInterface::class);
    }

    public function testGetEntityIdWithValidEntity(): void
    {
        $controller = $this->createController();

        $entity = new class {
            public function getId(): int
            {
                return 42;
            }
        };

        $id = $controller->publicGetEntityId($entity);
        self::assertSame(42, $id);
    }

    public function testGetEntityIdThrowsForEntityWithoutGetId(): void
    {
        $controller = $this->createController();
        $entity = new \stdClass();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('must implement a getId() method');

        $controller->publicGetEntityId($entity);
    }

    public function testGetEntityLabelWithStringableEntity(): void
    {
        $controller = $this->createController();

        $entity = new class implements \Stringable {
            public function __toString(): string
            {
                return 'My Entity';
            }

            public function getId(): int
            {
                return 1;
            }
        };

        self::assertSame('My Entity', $controller->publicGetEntityLabel($entity));
    }

    public function testGetEntityLabelFallsBackToClassAndId(): void
    {
        $controller = $this->createController();

        $entity = new class {
            public function getId(): int
            {
                return 99;
            }
        };

        $label = $controller->publicGetEntityLabel($entity);
        self::assertStringContainsString('#99', $label);
    }

    public function testGetBaseLayout(): void
    {
        $controller = $this->createController();
        self::assertSame('admin/layout/base.html.twig', $controller->publicGetBaseLayout());
    }

    public function testGetNewValidationGroups(): void
    {
        $controller = $this->createController();
        self::assertSame(['create'], $controller->publicGetNewValidationGroups());
    }

    public function testGetEditValidationGroups(): void
    {
        $controller = $this->createController();
        self::assertSame(['edit'], $controller->publicGetEditValidationGroups());
    }

    public function testGetNewFormOptionsContainsValidationGroups(): void
    {
        $controller = $this->createController();
        $entity = new \stdClass();

        $options = $controller->publicGetNewFormOptions($entity);

        self::assertArrayHasKey('validation_groups', $options);
        self::assertSame(['create'], $options['validation_groups']);
    }

    public function testGetEditFormOptionsContainsValidationGroups(): void
    {
        $controller = $this->createController();
        $entity = new \stdClass();

        $options = $controller->publicGetEditFormOptions($entity);

        self::assertArrayHasKey('validation_groups', $options);
        self::assertSame(['edit'], $options['validation_groups']);
    }

    public function testTemplateDefaults(): void
    {
        $controller = $this->createController();

        self::assertSame('@SymkitCrud/crud/entity_form.html.twig', $controller->publicGetNewTemplate());
        self::assertSame('@SymkitCrud/crud/entity_form.html.twig', $controller->publicGetEditTemplate());
        self::assertSame('@SymkitCrud/crud/show.html.twig', $controller->publicGetShowTemplate());
        self::assertSame('@SymkitCrud/crud/index.html.twig', $controller->publicGetIndexTemplate());
    }

    public function testTranslatableMessages(): void
    {
        $controller = $this->createController();

        $title = $controller->publicGetIndexPageTitle();
        self::assertInstanceOf(TranslatableMessage::class, $title);

        $description = $controller->publicGetIndexPageDescription();
        self::assertInstanceOf(TranslatableMessage::class, $description);

        $createLabel = $controller->publicGetCreateLabel();
        self::assertInstanceOf(TranslatableMessage::class, $createLabel);

        $csrfMessage = $controller->publicGetInvalidCsrfMessage();
        self::assertInstanceOf(TranslatableMessage::class, $csrfMessage);
    }

    public function testSuccessMessages(): void
    {
        $controller = $this->createController();
        $entity = new class implements \Stringable {
            public function __toString(): string
            {
                return 'Test Entity';
            }
        };

        $createMsg = $controller->publicGetCreateSuccessMessage($entity);
        self::assertInstanceOf(TranslatableMessage::class, $createMsg);

        $updateMsg = $controller->publicGetUpdateSuccessMessage($entity);
        self::assertInstanceOf(TranslatableMessage::class, $updateMsg);

        $deleteMsg = $controller->publicGetDeleteSuccessMessage($entity);
        self::assertInstanceOf(TranslatableMessage::class, $deleteMsg);
    }

    public function testConfigureListFieldsDefaultsToEmptyArray(): void
    {
        $controller = $this->createController();
        self::assertSame([], $controller->publicConfigureListFields());
    }

    public function testConfigureShowFieldsDelegatesToListFields(): void
    {
        $controller = $this->createController();
        self::assertSame($controller->publicConfigureListFields(), $controller->publicConfigureShowFields());
    }

    public function testConfigureShowSectionsHasGeneralSection(): void
    {
        $controller = $this->createController();
        $sections = $controller->publicConfigureShowSections();

        self::assertArrayHasKey('general', $sections);
        self::assertSame('Details', $sections['general']['label']);
        self::assertArrayHasKey('icon', $sections['general']);
        self::assertArrayHasKey('description', $sections['general']);
        self::assertArrayHasKey('fields', $sections['general']);
    }

    public function testConfigureSearchFieldsDefaultsToEmptyArray(): void
    {
        $controller = $this->createController();
        self::assertSame([], $controller->publicConfigureSearchFields());
    }

    private function createController(): TestCrudController
    {
        return new TestCrudController(
            $this->persistenceManager,
            $this->pageContextBuilder,
        );
    }
}

/**
 * Concrete implementation exposing protected methods for testing.
 */
class TestCrudController extends AbstractCrudController
{
    protected function getEntityClass(): string
    {
        return 'App\\Entity\\TestEntity';
    }

    protected function getFormClass(): string
    {
        return 'App\\Form\\TestEntityType';
    }

    protected function getRoutePrefix(): string
    {
        return 'app_test_entity';
    }

    // Expose protected methods for testing

    public function publicGetEntityId(object $entity): string|int
    {
        return $this->getEntityId($entity);
    }

    public function publicGetEntityLabel(object $entity): string
    {
        return $this->getEntityLabel($entity);
    }

    public function publicGetBaseLayout(): string
    {
        return $this->getBaseLayout();
    }

    public function publicGetNewValidationGroups(): array
    {
        return $this->getNewValidationGroups();
    }

    public function publicGetEditValidationGroups(): array
    {
        return $this->getEditValidationGroups();
    }

    public function publicGetNewFormOptions(object $entity): array
    {
        return $this->getNewFormOptions($entity);
    }

    public function publicGetEditFormOptions(object $entity): array
    {
        return $this->getEditFormOptions($entity);
    }

    public function publicGetNewTemplate(): string
    {
        return $this->getNewTemplate();
    }

    public function publicGetEditTemplate(): string
    {
        return $this->getEditTemplate();
    }

    public function publicGetShowTemplate(): string
    {
        return $this->getShowTemplate();
    }

    public function publicGetIndexTemplate(): string
    {
        return $this->getIndexTemplate();
    }

    public function publicGetIndexPageTitle(): TranslatableMessage
    {
        return $this->getIndexPageTitle();
    }

    public function publicGetIndexPageDescription(): TranslatableMessage
    {
        return $this->getIndexPageDescription();
    }

    public function publicGetCreateLabel(): TranslatableMessage
    {
        return $this->getCreateLabel();
    }

    public function publicGetCreateSuccessMessage(object $entity): TranslatableMessage
    {
        return $this->getCreateSuccessMessage($entity);
    }

    public function publicGetUpdateSuccessMessage(object $entity): TranslatableMessage
    {
        return $this->getUpdateSuccessMessage($entity);
    }

    public function publicGetDeleteSuccessMessage(object $entity): TranslatableMessage
    {
        return $this->getDeleteSuccessMessage($entity);
    }

    public function publicGetInvalidCsrfMessage(): TranslatableMessage
    {
        return $this->getInvalidCsrfMessage();
    }

    public function publicConfigureListFields(): array
    {
        return $this->configureListFields();
    }

    public function publicConfigureShowFields(): array
    {
        return $this->configureShowFields();
    }

    public function publicConfigureShowSections(): array
    {
        return $this->configureShowSections();
    }

    public function publicConfigureSearchFields(): array
    {
        return $this->configureSearchFields();
    }
}
