<?php class TheExtensionLab_MegaMenuBubbleLayerCompat_Model_Observer
{
    public function megamenuGetfilterurlAfter(Varien_Event_Observer $observer)
    {
        $urlData = $observer->getUrlData();

        $this->_replaceOptionValue($urlData);
        $this->_replaceAttributeCode($urlData);
        $this->_regenerateUrl($urlData);

        return $this;
    }

    private function _regenerateUrl($urlData)
    {
        $query = array(
            $urlData->getAttributeCode() => $urlData->getValue(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null,
        );

        $requestPath = '';
        $urlRewritesCollection = Mage::registry('megamenu_url_rewrites');
        $categoryId = $urlData->getCategoryId();

        if (isset($urlRewritesCollection['category/' . $categoryId])) {
            $requestPath = $urlRewritesCollection['category/' . $categoryId]->getRequestPath();
        }


        $newUrl = Mage::getUrl($requestPath, array('_query' => $query));
        $newUrl = str_replace('/?', '?', $newUrl);
        $urlData->setUrl($newUrl);

        return $urlData;
    }

    private function _replaceAttributeCode($urlData)
    {
        $urlData->setAttributeCode($this->_getHelper()->getAttributeRequestVar($urlData->getAttributeCode()));
        return $urlData;
    }

    private function _replaceOptionValue($urlData){
        $value = $this->_getHelper()->getOptionKey($urlData->getAttributeCode(), $urlData->getValue());
        $urlData->setValue($value);
        return $urlData;
    }

    private function _getHelper(){
        return Mage::helper('bubble_layer');
    }
}