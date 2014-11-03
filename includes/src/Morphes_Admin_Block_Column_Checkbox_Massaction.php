<?php
/**
 * @category    Morphes
 * @package     Morphes_Admin
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @author Morphes Team
 *
 */
class Morphes_Admin_Block_Column_Checkbox_Massaction extends Morphes_Admin_Block_Column_Checkbox {
    public function renderCss() {
        return parent::renderCss().' ct-massaction';
    }
}