<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Webimpacto\TotalOrders\Block;

class Totalorders extends \Magento\Framework\View\Element\Template
{

    protected $checkoutSession;
    protected $customerSession;
    protected $_orderFactory;
    protected $orderCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->_orderFactory = $orderFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getEmailAddress()
    {
        return $this->checkoutSession->getLastRealOrder()->getCustomerEmail();
    }

    public function getTotalOrders()
    {

        if ($this->customerSession->isLoggedIn()){
            $total=0;
            $orders = $this->orderCollectionFactory->create()
            ->addAttributeToFilter('customer_email', $this->getEmailAddress());
            foreach ($orders as $order) {
                $total=$total+$order->getGrandTotal();
            }
            return $total;
        }
        return null;
    }
}
