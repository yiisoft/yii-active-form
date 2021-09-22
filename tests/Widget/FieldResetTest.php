<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Html\Html;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldResetTest extends TestCase
{
    use TestTrait;

    public function testAutoIdPrefix(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="reset" id="s-1" name="s-1">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->resetButton(['autoIdPrefix' => 's-'])->render(),
        );
    }

    public function testAttributes(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="reset" id="reset-1" name="reset-1" disabled>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->resetButton(['disabled' => true])->render(),
        );
    }

    public function testId(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="reset" id="test-id" name="test-id">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->resetButton(['id' => 'test-id'])->render(),
        );
    }

    public function testName(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="reset" id="reset-1" name="test-name">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->resetButton(['name' => 'test-name'])->render(),
        );
    }

    public function testRender(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="reset" id="reset-1" name="reset-1">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->resetButton()->render(),
        );
    }

    public function testValue(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="reset" id="reset-1" name="reset-1" value="Reseteable">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->resetButton(['value' => 'Reseteable'])->render(),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->setInaccessibleProperty(new Html(), 'generateIdCounter', []);
    }
}
