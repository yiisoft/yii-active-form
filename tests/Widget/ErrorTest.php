<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\PersonalForm;
use Yiisoft\Form\Tests\TestSupport\Validator\ValidatorMock;
use Yiisoft\Form\Widget\Error;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Widget\WidgetFactory;

final class ErrorTest extends TestCase
{
    private array $record = [];
    private PersonalForm $formModel;

    public function testImmutability(): void
    {
        $error = Error::widget();
        $this->assertNotSame($error, $error->message(''));
        $this->assertNotSame($error, $error->messageCallback([]));
        $this->assertNotSame($error, $error->tag());
    }

    public function testMessage(): void
    {
        $html = Error::widget()->config($this->formModel, 'name')->message('This is custom error message.')->render();
        $this->assertSame('<div>This is custom error message.</div>', $html);
    }

    public function testMessageCallback(): void
    {
        $html = Error::widget()
            ->config($this->formModel, 'name')
            ->messageCallback([$this->formModel, 'customError'])
            ->render();
        $this->assertSame('<div>This is custom error message.</div>', $html);
    }

    public function testMessageCallbackWithNoEncode(): void
    {
        $html = Error::widget()
            ->config($this->formModel, 'name', ['encode' => false])
            ->messageCallback([$this->formModel, 'customErrorWithIcon'])
            ->render();
        $this->assertSame('<div>(&#10006;) This is custom error message.</div>', $html);
    }

    public function testRender(): void
    {
        $this->assertSame(
            '<div>Value cannot be blank.</div>',
            Error::widget()->config($this->formModel, 'name')->render(),
        );
    }

    public function testTag(): void
    {
        $this->assertSame(
            'Value cannot be blank.',
            Error::widget()->config($this->formModel, 'name')->tag()->render(),
        );
        $this->assertSame(
            '<span>Value cannot be blank.</span>',
            Error::widget()->config($this->formModel, 'name')->tag('span')->render(),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new PersonalForm();
        $this->formModel->load(['PersonalForm' => ['name' => '']]);
        $validator = $this->createValidatorMock();
        $validator->validate($this->formModel);
    }

    private function createValidatorMock(): ValidatorInterface
    {
        return new ValidatorMock();
    }
}
