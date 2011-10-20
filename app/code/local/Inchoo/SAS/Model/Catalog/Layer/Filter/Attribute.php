<?php

class Inchoo_SAS_Model_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{

	protected function getSortingTemplate()
	{
		$blockName = Mage::getStoreConfig('sas/layersorting/prefix') . '_' . $this->getAttributeModel()->getAttributeCode();
		$layout = Mage::app()->getFrontController()->getAction()->getLayout();

		if ($block = $layout->getBlock($blockName)) {
			$result = preg_split('/[\r\n]+/', $block->toHtml(), -1, PREG_SPLIT_NO_EMPTY);
			return $result;
		}

		return false;
	}

	protected function _getItemsData() {
		$items = parent::_getItemsData();
		$templateArr = $this->getSortingTemplate();

		if (!$templateArr) {
			return $items;
		}

		// Do sorting
		rsort($templateArr);
		$foundMatch = false;
		$result = array();
		foreach ($items as $item) {
			foreach ($templateArr as $t) {
				if ($t == $item['label']) {
					$foundMatch = true;
					array_unshift($result, $item);
				}
			}

			if (!$foundMatch) {
				array_push($result, $item);
			}
			$foundMatch = false;
		}

		return $result;
	}

}
