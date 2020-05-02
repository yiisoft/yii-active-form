<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use Yiisoft\Form\Tests\TestCase;
use Yiisoft\Form\Tests\Stub\StubForm;
use Yiisoft\Form\Widget\PasswordInput;

final class PasswordInputTest extends TestCase
{
    public function testPasswordInput(): void
    {
        $form = new StubForm();

        $expected = '<input type="password" id="stubform-fieldstring" name="StubForm[fieldString]" placeholder="Field String">';
        $created = PasswordInput::widget()
            ->config($form, 'fieldString')
            ->run();
        $this->assertEquals($expected, $created);

        $expected = '<input type="password" id="stubform-fieldstring" class="test" name="StubForm[fieldString]" value="value" placeholder="Field String">';
        $created = PasswordInput::widget()
            ->config($form, 'fieldString', ['class' => 'test', 'value' => 'value'])
            ->run();
        $this->assertEquals($expected, $created);
    }
}
