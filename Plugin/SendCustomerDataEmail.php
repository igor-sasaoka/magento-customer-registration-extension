<?php
declare(strict_types=1);

namespace Sasa\CustomerRegistration\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Sasa\CustomerRegistration\Service\BuildCustomerDataMailTransport;

/**
 * Responsible to send email with customer data
 */
class SendCustomerDataEmail
{
    /**
     * @var BuildCustomerDataMailTransport
     */
    private $buildCustomerDataMailTransport;

    /**
     * @param BuildCustomerDataMailTransport $buildCustomerDataMailTransport
     */
    public function __construct(
        BuildCustomerDataMailTransport $buildCustomerDataMailTransport
    ) {
        $this->buildCustomerDataMailTransport = $buildCustomerDataMailTransport;
    }

    /**
     * Send email with customer data
     *
     * @param AccountManagement $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     * @throws LocalizedException
     * @throws MailException
     */
    public function afterCreateAccount(AccountManagement $subject, CustomerInterface $result): CustomerInterface
    {
        $transport = $this->buildCustomerDataMailTransport->execute($result);
        if ($transport) {
            $transport->sendMessage();
        }

        return $result;
    }
}
