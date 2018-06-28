<?php
namespace TYPO3\CMS\Extbase\Tests\Unit\Validation\Validator;

/*                                                                        *
 * This script belongs to the Extbase framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use TYPO3\CMS\Extbase\Validation\Exception\NoSuchValidatorException;

/**
 * Test case
 */
class ConjunctionValidatorTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function addingValidatorsToAJunctionValidatorWorks()
    {
        $proxyClassName = $this->buildAccessibleProxy(\TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator::class);
        $conjunctionValidator = new $proxyClassName([]);
        $mockValidator = $this->createMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class);
        $conjunctionValidator->addValidator($mockValidator);
        $this->assertTrue($conjunctionValidator->_get('validators')->contains($mockValidator));
    }

    /**
     * @test
     */
    public function allValidatorsInTheConjunctionAreCalledEvenIfOneReturnsError()
    {
        $validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator([]);
        $validatorObject = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $validatorObject->expects($this->once())->method('validate')->will($this->returnValue(new \TYPO3\CMS\Extbase\Error\Result()));
        $errors = new \TYPO3\CMS\Extbase\Error\Result();
        $errors->addError(new \TYPO3\CMS\Extbase\Error\Error('Error', 123));
        $secondValidatorObject = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $secondValidatorObject->expects($this->once())->method('validate')->will($this->returnValue($errors));
        $thirdValidatorObject = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $thirdValidatorObject->expects($this->once())->method('validate')->will($this->returnValue(new \TYPO3\CMS\Extbase\Error\Result()));
        $validatorConjunction->addValidator($validatorObject);
        $validatorConjunction->addValidator($secondValidatorObject);
        $validatorConjunction->addValidator($thirdValidatorObject);
        $validatorConjunction->validate('some subject');
    }

    /**
     * @test
     */
    public function validatorConjunctionReturnsNoErrorsIfAllJunctionedValidatorsReturnNoErrors()
    {
        $validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator([]);
        $validatorObject = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $validatorObject->expects($this->any())->method('validate')->will($this->returnValue(new \TYPO3\CMS\Extbase\Error\Result()));
        $secondValidatorObject = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $secondValidatorObject->expects($this->any())->method('validate')->will($this->returnValue(new \TYPO3\CMS\Extbase\Error\Result()));
        $validatorConjunction->addValidator($validatorObject);
        $validatorConjunction->addValidator($secondValidatorObject);
        $this->assertFalse($validatorConjunction->validate('some subject')->hasErrors());
    }

    /**
     * @test
     */
    public function validatorConjunctionReturnsErrorsIfOneValidatorReturnsErrors()
    {
        $validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator([]);
        $validatorObject = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $errors = new \TYPO3\CMS\Extbase\Error\Result();
        $errors->addError(new \TYPO3\CMS\Extbase\Error\Error('Error', 123));
        $validatorObject->expects($this->any())->method('validate')->will($this->returnValue($errors));
        $validatorConjunction->addValidator($validatorObject);
        $this->assertTrue($validatorConjunction->validate('some subject')->hasErrors());
    }

    /**
     * @test
     */
    public function removingAValidatorOfTheValidatorConjunctionWorks()
    {
        /** @var \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface */
        $validatorConjunction = $this->getAccessibleMock(\TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator::class, ['dummy'], [[]], '', true);
        $validator1 = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $validator2 = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $validatorConjunction->addValidator($validator1);
        $validatorConjunction->addValidator($validator2);
        $validatorConjunction->removeValidator($validator1);
        $this->assertFalse($validatorConjunction->_get('validators')->contains($validator1));
        $this->assertTrue($validatorConjunction->_get('validators')->contains($validator2));
    }

    /**
     * @test
     */
    public function removingANotExistingValidatorIndexThrowsException()
    {
        $this->expectException(NoSuchValidatorException::class);
        $this->expectExceptionCode(1207020177);
        $validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator([]);
        $validator = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $validatorConjunction->removeValidator($validator);
    }

    /**
     * @test
     */
    public function countReturnesTheNumberOfValidatorsContainedInTheConjunction()
    {
        $validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator([]);
        $validator1 = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $validator2 = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class)
            ->setMethods(['validate', 'getOptions'])
            ->getMock();
        $this->assertSame(0, count($validatorConjunction));
        $validatorConjunction->addValidator($validator1);
        $validatorConjunction->addValidator($validator2);
        $this->assertSame(2, count($validatorConjunction));
    }
}
