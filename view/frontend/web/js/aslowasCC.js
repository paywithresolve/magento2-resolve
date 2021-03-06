/**
 * Copyright © 2016 Resolve. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint jquery:true*/
define(["jquery",
    "mage/translate",
    "Resolve_Resolve/js/model/aslowas",
    "Magento_Checkout/js/model/quote"
], function ($, $t, aslowas, quote) {
    "use strict"

    var self;
    $.widget('mage.aslowasCC',{
        options: {
        },

        /**
         * Specify default price
         */
        initPrice: function(newValue) {
            var price = quote.getTotals()(), result;
            if (newValue) {
                price = newValue;
            }
            if (price && price.base_grand_total) {
                if (newValue) {
                    result = price.base_grand_total.toString();
                } else {
                    result = price.base_grand_total;
                }
                aslowas.process(result, this.options);
            }
        },

        /**
         * Create as low as widget
         *
         * @private
         */
        _create: function() {
            self = this;
            if (typeof resolve == "undefined") {
                $.when(aslowas.loadScript(self.options)).done(function() {
                    self.initPrice();
                });
            } else {
                self.initPrice();
            }
            quote.totals.subscribe(function(newValue) {
                self.initPrice(newValue);
            });
        }
    });
    return $.mage.aslowasCC
});
