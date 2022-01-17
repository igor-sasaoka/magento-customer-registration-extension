<?php

declare(strict_types=1);

namespace Sasa\CustomerRegistration\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AccountManagement;

class SanitizeCustomerName
{
    /**
     * Removes white spaces from customer firstname
     *
     * @param AccountManagement $subject
     * @param CustomerInterface $customer
     * @param string|null $password
     * @param string $redirectUrl
     * @return array
     */
    public function beforeCreateAccount(
        AccountManagement $subject,
        CustomerInterface $customer,
        string $password = null,
        string $redirectUrl = ''
    ): array
    {
        $firstname = $customer->getFirstname();
        $customer->setFirstname(str_replace(' ', '', $firstname));

        return [$customer, $password, $redirectUrl];
    }
}
