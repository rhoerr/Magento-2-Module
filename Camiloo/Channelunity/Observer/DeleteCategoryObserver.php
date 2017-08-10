<?php

/**
 * ChannelUnity connector for Magento Commerce
 *
 * @category   Camiloo
 * @package    Camiloo_Channelunity
 * @copyright  Copyright (c) 2016 ChannelUnity Limited (http://www.channelunity.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * ChannelUnity observers.
 * Posts events to the CU cloud when various Magento events occur.
 */

namespace Camiloo\Channelunity\Observer;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer;
use Camiloo\Channelunity\Model\Helper;
use Camiloo\Channelunity\Model\Categories;

class DeleteCategoryObserver implements ObserverInterface
{
    private $helper;
    private $cucategories;
    
    public function __construct(Helper $helper, Categories $categories)
    {
        $this->helper = $helper;
        $this->cucategories = $categories;
    }

    public function execute(Observer $observer)
    {
        $this->helper->logInfo("Observer called: Delete Category");
        $categoryId = $observer->getData('category')->getId();
        
        $myStoreURL = $this->helper->getBaseUrl()."channelunity/api/index";
        $myStoreURL = str_replace('http://', 'http%://', $myStoreURL);
        $myStoreURL = str_replace('https://', 'http%://', $myStoreURL);
        
        // Create XML
        $xml = <<<XML
<CategoryDelete>
    <SourceURL>{$myStoreURL}</SourceURL>
    <DeletedCategoryId>{$categoryId}</DeletedCategoryId>
</CategoryDelete>
XML;
            // Send XML to CU
            $this->helper->postToChannelUnity($xml, 'categoryDelete');
    }
}
