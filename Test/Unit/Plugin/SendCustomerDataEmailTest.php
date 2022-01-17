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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\TransportInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sasa\CustomerRegistration\Plugin\SendCustomerDataEmail;
use Sasa\CustomerRegistration\Service\BuildCustomerDataMailTransport;

/**
 * Test for interceptor class
 */
class SendCustomerDataEmailTest extends TestCase
{
    /**
     * @var SendCustomerDataEmail
     */
    private $object;

    /**
     * @var MockObject|BuildCustomerDataMailTransport
     */
    private $buildCustomerDataMailTransport;

    /**
     * @var TransportInterface|MockObject
     */
    private $transport;

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
     * @throws LocalizedException
     * @throws MailException
     */
    public function testAfterCreateAccount(): void
    {
        $expected = $this->customer;

        $this->buildCustomerDataMailTransport->expects($this->once())
            ->method('execute')
            ->willReturn($this->transport);
        $this->transport->expects($this->once())
            ->method('sendMessage');

        $result = $this->object->afterCreateAccount(
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
        $this->object = new SendCustomerDataEmail(
            $this->buildCustomerDataMailTransport
        );
    }

    /**
     * SetUpMockObjects
     *
     * @return void
     */
    private function setUpMockObjects(): void
    {
        $this->buildCustomerDataMailTransport = $this->createMock(
            BuildCustomerDataMailTransport::class
        );
        $this->transport = $this->createMock(TransportInterface::class);
        $this->customer = $this->createMock(CustomerInterface::class);
        $this->accountManagement = $this->createMock(AccountManagement::class);
    }
}
