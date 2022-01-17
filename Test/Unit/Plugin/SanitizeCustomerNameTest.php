<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2022 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Sasa\CustomerRegistration\Test\Unit\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AccountManagement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sasa\CustomerRegistration\Plugin\SanitizeCustomerName;

/**
 * Test for interceptor class
 */
class SanitizeCustomerNameTest extends TestCase
{
    /**
     * @var SanitizeCustomerName
     */
    private $object;

    /**
     * @var CustomerInterface|MockObject
     */
    private $customer;

    /**
     * @var AccountManagement|MockObject
     */
    private $accountManagement;

    /**
     * TestBeforeCreateAccount
     *
     * @return void
     */
    public function testBeforeCreateAccount(): void
    {
        $expected = [$this->customer, null, ''];

        $this->customer->expects($this->once())
            ->method('getFirstname')
            ->willReturn('first name');
        $this->customer->expects($this->once())
            ->method('setFirstname');

        $result = $this->object->beforeCreateAccount(
            $this->accountManagement,
            $this->customer
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * SetUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->setUpMockObjects();
        $this->object = new SanitizeCustomerName();
    }

    /**
     * SetUpMockObjects
     *
     * @return void
     */
    private function setUpMockObjects(): void
    {
        $this->customer = $this->createMock(CustomerInterface::class);
        $this->accountManagement = $this->createMock(AccountManagement::class);
    }
}
