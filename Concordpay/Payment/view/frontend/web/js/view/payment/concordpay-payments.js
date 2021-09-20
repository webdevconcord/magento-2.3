/*browser:true*/
/*global define*/
define(
  [
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
  ],
  function (Component, rendererList) {
    'use strict';
    rendererList.push(
      {
        type: 'concordpay',
        component: 'Concordpay_Payment/js/view/payment/method-renderer/concordpay-method'
      }
    );

    return Component.extend({});
  }
);
