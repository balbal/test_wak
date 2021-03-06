<?php

/**
 * 2018 Alma / Nabla SAS
 *
 * THE MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @author    Alma / Nabla SAS <contact@getalma.eu>
 * @copyright 2018 Alma / Nabla SAS
 * @license   https://opensource.org/licenses/MIT The MIT License
 *
 */
class Alma_Installments_Helper_Config extends Mage_Core_Helper_Abstract {

    const CONFIG_ACTIVE = 'payment/alma_installments/active';
    const CONFIG_SORT_ORDER = 'payment/alma_installments/sort_order';
    const CONFIG_LOGGING = 'payment/alma_installments/logging';
    const CONFIG_LIVE_API_KEY = 'payment/alma_installments/live_api_key';
    const CONFIG_TEST_API_KEY = 'payment/alma_installments/test_api_key';
    const CONFIG_API_MODE = 'payment/alma_installments/api_mode';
    const CONFIG_SHOW_ELIGIBILITY_MESSAGE = 'payment/alma_installments/show_eligibility_message';
    const CONFIG_ELIGIBILITY_MESSAGE = 'payment/alma_installments/eligibility_message';
    const CONFIG_NON_ELIGIBILITY_MESSAGE = 'payment/alma_installments/non_eligibility_message';
    const CONFIG_TITLE = 'payment/alma_installments/title';
    const CONFIG_DESCRIPTION = 'payment/alma_installments/description';
    const CONFIG_EXCLUDED_PRODUCT_TYPES = 'payment/alma_installments/excluded_product_types';
    const CONFIG_EXCLUDED_PRODUCTS_MESSAGE = 'payment/alma_installments/excluded_products_message';
    const CONFIG_PNX_ENABLED = 'payment/alma_installments/p%dx_enabled';
    const CONFIG_PNX_MIN_AMOUNT = 'payment/alma_installments/p%dx_min_amount';
    const CONFIG_PNX_MAX_AMOUNT = 'payment/alma_installments/p%dx_max_amount';
    const CONFIG_PNX_MAX_N = 'payment/alma_installments/pnx_max_n';
    const CONFIG_FULLY_CONFIGURED = 'payment/alma_installments/fully_configured';

    // bsd_informatique
    public function is_empty($var) {
        return empty($var);
    }

    public function get($field, $default = null, $storeId = null) {
        $value = Mage::getStoreConfig($field, $storeId);

        if ($value === null) {
            $value = $default;
        }

        return $value;
    }

    public function isActive() {
        return (bool) (int) $this->get(self::CONFIG_ACTIVE);
    }

    public function canLog() {
        return (bool) (int) $this->get(self::CONFIG_LOGGING, false);
    }

    public function getActiveMode() {
        return $this->get(self::CONFIG_API_MODE, 'test');
    }

    public function getActiveAPIKey() {
        $mode = $this->getActiveMode();

        switch ($mode) {
            case 'live':
                return $this->getLiveKey();
            default:
                return $this->getTestKey();
        }
    }

 //   Alma-Auth sk_live_6bfQCYwNkG60qooKCSsqM61n
//     Alma-Auth sk_test_22D6FVT2HksuEkEowSga4T3L
    public function getLiveKey() {
        return "sk_live_6bfQCYwNkG60qooKCSsqM61n";
        return $this->get(self::CONFIG_LIVE_API_KEY, '');
    }

    public function getTestKey() {
        return "sk_test_22D6FVT2HksuEkEowSga4T3L";
        return $this->get(self::CONFIG_TEST_API_KEY, '');
    }

    public function needsAPIKeys() {
        // bsd_informatique
        return $this->is_empty(trim($this->getLiveKey())) || $this->is_empty(trim($this->getTestKey()));
    }

    public function getEligibilityMessage() {
        return $this->get(self::CONFIG_ELIGIBILITY_MESSAGE);
    }

    public function getNonEligibilityMessage() {
        return $this->get(self::CONFIG_NON_ELIGIBILITY_MESSAGE);
    }

    public function showEligibilityMessage() {
        return (bool) (int) $this->get(self::CONFIG_SHOW_ELIGIBILITY_MESSAGE);
    }

    public function getPaymentButtonTitle() {
        return $this->get(self::CONFIG_TITLE);
    }

    public function getPaymentButtonDescription() {
        return $this->get(self::CONFIG_DESCRIPTION);
    }

    public function getExcludedProductTypes() {
        return explode(',', $this->get(self::CONFIG_EXCLUDED_PRODUCT_TYPES));
    }

    public function getExcludedProductsMessage() {
        return $this->get(self::CONFIG_EXCLUDED_PRODUCTS_MESSAGE);
    }

    public function isFullyConfigured() {
        return !$this->needsAPIKeys() && (bool) (int) $this->get(self::CONFIG_FULLY_CONFIGURED, false);
    }

    public function isPnXEnabled($n) {
        return (bool) (int) $this->get(sprintf(self::CONFIG_PNX_ENABLED, $n), $n == 3);
    }

    public function pnxMinAmount($n, $merchant = null) {
        $min = $merchant ? $merchant->minimum_purchase_amount : 10000;
        return (int) $this->get(sprintf(self::CONFIG_PNX_MIN_AMOUNT, $n), $min);
    }

    public function pnxMaxAmount($n, $merchant = null) {
        $max = $merchant ? $merchant->maximum_purchase_amount : 100000;
        return (int) $this->get(sprintf(self::CONFIG_PNX_MAX_AMOUNT, $n), $max);
    }

    public function pnxMaxN() {
        return (int) $this->get(self::CONFIG_PNX_MAX_N, 3);
    }

}
