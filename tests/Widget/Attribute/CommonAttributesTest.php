<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Widget\CommonAttributesWidget;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class CommonAttributesTest extends TestCase
{
    public function testAutofocus(): void
    {
        $this->assertSame('<test autofocus>', CommonAttributesWidget::widget()->autofocus()->render());
    }

    public function testDisabled(): void
    {
        $this->assertSame('<test disabled>', CommonAttributesWidget::widget()->disabled()->render());
    }

    public function testForm(): void
    {
        $this->assertSame(
            '<test form="test-form-id">',
            CommonAttributesWidget::widget()->form('test-form-id')->render(),
        );
    }

    public function testImmutability(): void
    {
        $commonAttributes = CommonAttributesWidget::widget();
        $this->assertNotSame($commonAttributes, $commonAttributes->autofocus());
        $this->assertNotSame($commonAttributes, $commonAttributes->disabled());
        $this->assertNotSame($commonAttributes, $commonAttributes->form(''));
        $this->assertNotSame($commonAttributes, $commonAttributes->readOnly());
        $this->assertNotSame($commonAttributes, $commonAttributes->required());
        $this->assertNotSame($commonAttributes, $commonAttributes->tabIndex());
    }

    public function testReadOnly(): void
    {
        $this->assertSame('<test readonly>', CommonAttributesWidget::widget()->readOnly()->render());
    }

    public function testRequired(): void
    {
        $this->assertSame('<test required>', CommonAttributesWidget::widget()->required()->render());
    }

    public function testTabIndex(): void
    {
        $this->assertSame('<test tabindex="5">', CommonAttributesWidget::widget()->tabIndex(5)->render());
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
    }
}
