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
use Psr\Log\LoggerInterface;
use Sasa\CustomerRegistration\Plugin\LogCustomerData;

/**
 * Test for interceptor class
 */
class LogCustomerDataTest extends TestCase
{
    /**
     * @var LogCustomerData
     */
    private $object;

    /**
     * @var MockObject|LoggerInterface
     */
    private $logger;

    /**
     * @var CustomerInterface|MockObject
     */
    private $customer;

    /**
     * @var AccountManagement|MockObject
     */
    private $accountManagement;

    /**
     * TestAfterCreateAccount
     *
     * @return void
     */
    public function testAfterCreateAccount(): void
    {
        $expected = $this->customer;

        $this->customer->expects($this->once())
            ->method('getFirstname')
            ->willReturn('firstname');
        $this->customer->expects($this->once())
            ->method('getLastname')
            ->willReturn('lastname');
        $this->customer->expects($this->once())
            ->method('getEmail')
            ->willReturn('customer@mail.com');
        $this->customer->expects($this->once())
            ->method('getCreatedAt')
            ->willReturn('xx-xx-xx xx:xx:xx');

        $this->logger->expects($this->once())
            ->method('info');

        $result = $this->object->afterCreateAccount($this->accountManagement, $this->customer);
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
        $this->object = new LogCustomerData($this->logger);
    }

    /**
     * SetUpMockObjects
     *
     * @return void
     */
    private function setUpMockObjects(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->customer = $this->createMock(CustomerInterface::class);
        $this->accountManagement = $this->createMock(AccountManagement::class);
    }
}
