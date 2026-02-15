# UI API Reference

> **Domain**: User Interface Components, Pages, Messaging, DataGrid, AJAX  
> **Last Updated**: February 15, 2026

[← Back to API Index](README.md)

---

## Overview

The UI namespace provides comprehensive components for building web-based user interfaces with Bootstrap 5 styling. It includes:

- **Core UI Framework**: UserInterface, page management, request handling
- **Components**: Button, Icon, Text with fluent interfaces
- **Pages**: Base classes for full pages and sub-pages with navigation
- **Messaging**: User notification system (success, error, warning, info)
- **DataGrid**: Table generation with rows, columns, and styling
- **AJAX**: Server-side AJAX method registration and handling

---

## Table of Contents

- [Core UI](#core-ui)
- [Components](#components)
- [Pages](#pages)
- [Messaging](#messaging)
- [DataGrid](#datagrid)
- [AJAX](#ajax)

---

## Core UI

### Mistralys\X4\UI\UIException

Base exception for UI-related errors.

**Extends:** `X4Exception`

---

### Mistralys\X4\UI\UserInterface

User interface handler - renders the UI.

#### Constants
```php
ERROR_NO_PAGES_REGISTERED: int = 105801
ERROR_PAGE_CLASS_NOT_FOUND: int = 105802
ERROR_INVALID_PAGE_CLASS: int = 105803
ERROR_UNKNOWN_PAGE_ID: int = 105804
THEME_SUPERHERO: string = 'superhero'
```

#### Methods
```php
__construct(X4Application $application, string $webrootURL, string $vendorURL = ''): void
getMessages(): Messages
static displayException(BaseException $e): void
addJSHead(string $statement): self
addJSOnload(string $statement): self
getTitle(): string
getRequest(): Request
getWebrootURL(): string
setUnitTestingURL(string $unitTestingURL): self
createDataGrid(): DataGrid
registerPage(string $urlName, string $className): void
registerAjaxMethod(AjaxMethodInterface $ajaxMethod): void
getAjaxMethods(): AjaxMethods
getPageClass(string $id): string
createPage(string $id): BasePage
getApplication(): X4Application
getActivePageID(): string
addInternalStylesheet(string $file): self
addExternalStylesheet(string $url): self
addVendorStylesheet(string $packageName, string $file): self
addInternalJS(string $file): self
addExternalJS(string $url): self
addVendorJS(string $packageName, string $file): self
makeFooterFixed(bool $fixed = true): self
setFooterContent(StringableInterface|string|null $content): self
render(): string
```

---

## Components

### Mistralys\X4\UI\Text

Text utility class with Bootstrap color support.

#### Methods
```php
__construct(string|number|Interface_Stringable|null $label): void
static create(string|number|Interface_Stringable|null $label = null): Text
colorSuccess(): self
colorWarning(): self
colorPrimary(): self
colorMuted(): self
colorDanger(): self
colorInfo(): self
setColorName(string $name): self
render(): string
```

**Usage:**
```php
$text = Text::create('Important')->colorDanger();
echo $text->render(); // <span class="text-danger">Important</span>
```

---

### Mistralys\X4\UI\Button

Button component with Bootstrap styling.

#### Methods
```php
__construct(string|Interface_Stringable|null $label): void
static create(string|Interface_Stringable|null $label = ''): Button
setLabel(string|Interface_Stringable|null $label): self
setIcon(Icon $icon): self
setTooltip(string|Interface_Stringable|null $tooltip): self
makeSubmit(string $name, string $value = ''): self
link(string $url, bool $newTab = false): self
click(string|Interface_Stringable|null $statement): self
colorPrimary(): self
colorSuccess(): self
colorDanger(): self
colorWarning(): self
colorInfo(): self
makeOutline(): self
setColorType(string $type): self
sizeLarge(): self
sizeSmall(): self
sizeExtraSmall(): self
render(): string
```

**Usage:**
```php
$button = Button::create('Save')
    ->setIcon(Icon::save())
    ->colorSuccess()
    ->click('handleSave()');
echo $button->render();
```

---

### Mistralys\X4\UI\Icon

Icon component using FontAwesome.

#### Methods
```php
__construct(string $name, string $type): void
static typeSolid(string $name): Icon
static typeRegular(string $name): Icon
colorPrimary(): self
colorSuccess(): self
colorDanger(): self
colorMuted(): self
colorWarning(): self
colorInfo(): self
setColorClass(string $class): self
setTooltip(string $text): self
static yes(): Icon
static no(): Icon
static delete(): Icon
static unpack(): Icon
static backup(): Icon
static back(): Icon
static analyze(): Icon
static previous(): Icon
static next(): Icon
static first(): Icon
static last(): Icon
static allItems(): Icon
static save(): Icon
render(): string
```

**Usage:**
```php
$icon = Icon::save()->colorSuccess()->setTooltip('Save changes');
echo $icon->render();
```

---

### Mistralys\X4\UI\Console

Console output utility.

#### Methods
```php
// (Public interface not extensively documented in extraction)
```

---

## Pages

### Mistralys\X4\UI\Page\BasePage

Base class for application pages.

#### Constants
```php
REQUEST_PARAM_PAGE: string = 'page'
REQUEST_PARAM_VIEW: string = 'view'
```

#### Methods
```php
__construct(UserInterface $ui): void
getRequest(): Request
getApplication(): X4Application
getUI(): UserInterface
getID(): string
abstract getTitle(): string
abstract getSubtitle(): string
abstract getAbstract(): string
abstract getNavTitle(): string
abstract preRender(): void
abstract _render(): void
abstract getNavItems(): array // Returns NavItem[]
redirectWithSuccessMessage(string $url, string|StringableInterface|null $message, ?int $code = null): never
redirectWithErrorMessage(string $url, string|StringableInterface|null $message, int $code): never
redirectWithInfoMessage(string $url, string|StringableInterface|null $message, ?int $code = null): never
redirectWithWarningMessage(string $url, string|StringableInterface|null $message, int $code): never
redirect(string $url): never
getURL(array $params = []): string
abstract getURLParams(): array
render(): string
```

---

### Mistralys\X4\UI\Page\BasePageWithNav

Base class for pages with sub-navigation.

#### Constants
```php
ERROR_INVALID_SUBPAGE_ID: int = 89401
```

#### Methods
```php
__construct(UserInterface $ui): void
abstract getDefaultSubPageID(): string
abstract initSubPages(): void
getNavItems(): array
getSubPage(): BaseSubPage
```

---

### Mistralys\X4\UI\Page\BaseSubPage

Base class for sub-pages within a page.

#### Methods
```php
__construct(BasePage $page): void
getURL(array $params = []): string
generateOutput(): void
abstract getURLParams(): array
abstract isInSubnav(): bool
abstract getURLName(): string
abstract renderContent(): void
abstract getTitle(): string
abstract getSubtitle(): string
abstract getAbstract(): string
```

---

### Mistralys\X4\UI\Page\NavItem

Navigation item representation.

#### Methods
```php
__construct(string $label, string $url): void
getLabel(): string
getUrl(): string
isActive(): bool
```

---

### Mistralys\X4\UI\Page\PageNavItem

Page-specific navigation item.

#### Methods
```php
__construct(BasePage $page): void
```

---

## Messaging

### Mistralys\X4\UI\Messaging\Messages

Message collection handler for user notifications.

#### Constants
```php
TYPE_SUCCESS: string = 'success'
TYPE_INFO: string = 'info'
TYPE_WARNING: string = 'warning'
TYPE_ERROR: string = 'error'
```

#### Methods
```php
__construct(): void
writeToSession(): void
addSuccess(string|StringableInterface|null $message, ?int $code = null): self
addInfo(string|StringableInterface|null $message, ?int $code = null): self
addWarning(string|StringableInterface|null $message, int $code): self
addError(string|StringableInterface|null $message, int $code): self
addMessage(string|StringableInterface|null $message, string $type, ?int $code = null): self
getMessages(): array // Returns Message[]
hasMessages(): bool
clear(): self
```

**Usage:**
```php
$messages = new Messages();
$messages->addSuccess('Data saved successfully');
$messages->addError('Validation failed', 12345);
```

---

### Mistralys\X4\UI\Messaging\Message

Individual message instance.

#### Methods
```php
__construct(string $type, string|StringableInterface|null $message, ?int $code): void
getType(): string
getMessage(): string
getCode(): ?int
render(): string
```

---

## DataGrid

### Mistralys\X4\UI\DataGrid

Utility class used to generate HTML code for data grids.

#### Methods
```php
__construct(UserInterface $ui): void
getUI(): UserInterface
addColumn(string $keyName, string $label): GridColumn
addRow(GridRow $entry): self
createRow(): RegularRow
countColumns(): int
createMergedRow(): MergedRow
addRowFromArray(array $values): self
addRowFromObject(object $object): self
addRowsFromObjects(array $objects): self // Parameter: object[]
getColumns(): array // Returns GridColumn[]
getRows(): array // Returns GridRow[]
optionStriped(bool $enable): self
optionBordered(bool $enable): self
render(): string
```

**Usage:**
```php
$grid = $ui->createDataGrid();
$grid->addColumn('name', 'Name');
$grid->addColumn('value', 'Value');
$grid->addRowFromArray(['name' => 'Hull', 'value' => 5000]);
echo $grid->render();
```

---

### Mistralys\X4\UI\DataGrid\GridColumn

Represents a DataGrid column.

#### Methods
```php
// (Detailed method signatures in source)
```

---

### Mistralys\X4\UI\DataGrid\GridRow

Base class for DataGrid rows.

#### Methods
```php
// (Detailed method signatures in source)
```

---

### Mistralys\X4\UI\DataGrid\GridCell

Represents a DataGrid cell.

#### Methods
```php
// (Detailed method signatures in source)
```

---

## AJAX

### Mistralys\X4\UI\Ajax\AjaxMethodInterface

Interface for AJAX methods.

#### Methods
```php
getName(): string
process(): array
```

---

### Mistralys\X4\UI\Ajax\BaseAjaxMethod

Base class for AJAX methods.

#### Methods
```php
// (Detailed method signatures in source)
```

---

### Mistralys\X4\UI\Ajax\AjaxMethods

AJAX method registry.

#### Methods
```php
// (Detailed method signatures in source)
```

---

## Usage Patterns

### Creating a Custom Page

```php
class MyPage extends BasePage
{
    public function getTitle(): string { return 'My Page'; }
    public function getSubtitle(): string { return 'Description'; }
    public function getAbstract(): string { return 'Details'; }
    public function getNavTitle(): string { return 'Navigation Label'; }
    public function getURLParams(): array { return []; }
    public function getNavItems(): array { return []; }
    
    public function preRender(): void {
        // Initialize page data
    }
    
    public function _render(): void {
        echo '<h1>Page Content</h1>';
    }
}
```

### Creating UI Components

```php
// Button with icon
$saveButton = Button::create('Save Changes')
    ->setIcon(Icon::save())
    ->colorSuccess()
    ->click('handleSave()');

// Colored text
$errorText = Text::create('Error occurred')->colorDanger();

// DataGrid
$grid = $ui->createDataGrid()
    ->optionStriped(true)
    ->addColumn('id', 'ID')
    ->addColumn('name', 'Name')
    ->addRowFromObject($shipObject);
```

---

## Notes

- All UI components follow a **fluent interface** pattern (methods return `self` for chaining)
- Components integrate with **Bootstrap 5** for styling
- Pages must implement all abstract methods from base classes
- Messages are rendered with Bootstrap alert styling
- DataGrid automatically handles object-to-row conversion
