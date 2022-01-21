<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Yiisoft\Definitions\Exception\CircularReferenceException;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\Factory\NotFoundException;
use Yiisoft\Form\Tests\TestSupport\Form\LoginValidatorForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\ErrorSummary;
use Yiisoft\Form\Widget\Field;

final class ValidatorTest extends TestCase
{
    use TestTrait;

    private array $fieldConfig = [
        'errorClass()' => ['hasError'],
        'hintClass()' => ['info-class'],
        'invalidClass()' => ['is-invalid'],
        'validClass()' => ['is-valid'],
    ];

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testLoginAndPasswordValidatorInvalid(): void
    {
        $loginValidatorForm = new loginValidatorForm();
        $validator = $this->createValidatorMock();

        $loginValidatorForm->setAttribute('login', 'joe');
        $loginValidatorForm->setAttribute('password', '123456');
        $validator->validate($loginValidatorForm);

        $expected = <<<HTML
        <div>
        <label for="loginvalidatorform-login">Login</label>
        <input type="text" id="loginvalidatorform-login" class="is-invalid" name="LoginValidatorForm[login]" value="joe" required>
        </div>
        <div>
        <label for="loginvalidatorform-password">Password</label>
        <input type="text" id="loginvalidatorform-password" class="is-invalid" name="LoginValidatorForm[password]" value="123456" required>
        <div class="hasError">invalid login password</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget($this->fieldConfig)->text($loginValidatorForm, 'login')->render() . PHP_EOL .
            Field::widget($this->fieldConfig)->text($loginValidatorForm, 'password')->render()
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testLoginAndPasswordValidatorValid(): void
    {
        $loginValidatorForm = new loginValidatorForm();
        $validator = $this->createValidatorMock();

        $loginValidatorForm->setAttribute('login', 'admin');
        $loginValidatorForm->setAttribute('password', 'admin');
        $validator->validate($loginValidatorForm);

        $expected = <<<HTML
        <div>
        <label for="loginvalidatorform-login">Login</label>
        <input type="text" id="loginvalidatorform-login" class="is-valid" name="LoginValidatorForm[login]" value="admin" required>
        </div>
        <div>
        <label for="loginvalidatorform-password">Password</label>
        <input type="text" id="loginvalidatorform-password" class="is-valid" name="LoginValidatorForm[password]" value="admin" required>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget($this->fieldConfig)->text($loginValidatorForm, 'login')->render() . PHP_EOL .
            Field::widget($this->fieldConfig)->text($loginValidatorForm, 'password')->render()
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testLoginAndPasswordValidatorInvalidWithErrorSummary(): void
    {
        $loginValidatorForm = new loginValidatorForm();
        $validator = $this->createValidatorMock();

        $loginValidatorForm->setAttribute('login', 'joe');
        $loginValidatorForm->setAttribute('password', '123456');
        $validator->validate($loginValidatorForm);

        $expected = <<<HTML
        <div>
        <label for="loginvalidatorform-login">Login</label>
        <input type="text" id="loginvalidatorform-login" class="is-invalid" name="LoginValidatorForm[login]" value="joe" required>
        </div>
        <div>
        <label for="loginvalidatorform-password">Password</label>
        <input type="text" id="loginvalidatorform-password" class="is-invalid" name="LoginValidatorForm[password]" value="123456" required>
        </div>
        <div>
        <p>Please fix the following errors:</p>
        <ul>
        <li>invalid login password</li>
        </ul>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget($this->fieldConfig)
                ->error(null)
                ->text($loginValidatorForm, 'login')
                ->render() . PHP_EOL .
            Field::widget($this->fieldConfig)
                ->error(null)
                ->text($loginValidatorForm, 'password')
                ->render() . PHP_EOL .
            ErrorSummary::widget()->model($loginValidatorForm)->render(),
        );
    }

    /**
     * @throws CircularReferenceException|InvalidConfigException|NotFoundException|NotInstantiableException
     */
    public function testLoginAndPasswordValidatorValidWithErrorSummary(): void
    {
        $loginValidatorForm = new loginValidatorForm();
        $validator = $this->createValidatorMock();

        $loginValidatorForm->setAttribute('login', 'admin');
        $loginValidatorForm->setAttribute('password', 'admin');
        $validator->validate($loginValidatorForm);

        $expected = <<<HTML
        <div>
        <label for="loginvalidatorform-login">Login</label>
        <input type="text" id="loginvalidatorform-login" class="is-valid" name="LoginValidatorForm[login]" value="admin" required>
        </div>
        <div>
        <label for="loginvalidatorform-password">Password</label>
        <input type="text" id="loginvalidatorform-password" class="is-valid" name="LoginValidatorForm[password]" value="admin" required>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget($this->fieldConfig)->text($loginValidatorForm, 'login')->render() . PHP_EOL .
            Field::widget($this->fieldConfig)->text($loginValidatorForm, 'password')->render() .
            ErrorSummary::widget()->model($loginValidatorForm)->render(),
        );
    }
}
