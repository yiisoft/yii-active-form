<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\FormModel;
use Yiisoft\Form\FormModelInterface;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Tests\TestSupport\Form\LoginForm;

final class HtmlFormTest extends TestCase
{
    public function testGetAttributeHint(): void
    {
        $formModel = new LoginForm();
        $this->assertSame('Write your id or email.', HtmlForm::getAttributeHint($formModel, 'login'));

        $anonymousForm = new class () extends FormModel {
            private string $age = '';
        };
        $this->assertEmpty(HtmlForm::getAttributeHint($anonymousForm, 'age'));
    }

    public function testGetAttributeName(): void
    {
        $formModel = new LoginForm();
        $this->assertSame('login', HtmlForm::getAttributeName($formModel, '[0]login'));
        $this->assertSame('login', HtmlForm::getAttributeName($formModel, 'login[0]'));
        $this->assertSame('login', HtmlForm::getAttributeName($formModel, '[0]login[0]'));
    }

    public function testGetAttributeNameException(): void
    {
        $formModel = new LoginForm();

        $this->expectExceptionMessage("Attribute 'noExist' does not exist.");
        HtmlForm::getAttributeName($formModel, 'noExist');
    }

    public function testGetAttributeNameInvalid(): void
    {
        $formModel = new LoginForm();
        $this->expectExceptionMessage('Attribute name must contain word characters only.');
        HtmlForm::getAttributeName($formModel, 'content body');
    }

    public function dataGetInputName(): array
    {
        $loginForm = new LoginForm();
        $anonymousForm = new class () extends FormModel {
        };
        return [
            [$loginForm, '[0]content', 'LoginForm[0][content]'],
            [$loginForm, 'dates[0]', 'LoginForm[dates][0]'],
            [$loginForm, '[0]dates[0]', 'LoginForm[0][dates][0]'],
            [$loginForm, 'age', 'LoginForm[age]'],
            [$anonymousForm, 'dates[0]', 'dates[0]'],
            [$anonymousForm, 'age', 'age'],
        ];
    }

    /**
     * @dataProvider dataGetInputName
     *
     * @param FormModelInterface $form
     * @param string $attribute
     * @param string $expected
     */
    public function testGetInputName(FormModelInterface $form, string $attribute, string $expected): void
    {
        $this->assertSame($expected, HtmlForm::getInputName($form, $attribute));
    }

    public function testGetInputNameException(): void
    {
        $anonymousForm = new class () extends FormModel {
        };

        $this->expectExceptionMessage('formName() cannot be empty for tabular inputs.');
        HtmlForm::getInputName($anonymousForm, '[0]dates[0]');
    }
}
