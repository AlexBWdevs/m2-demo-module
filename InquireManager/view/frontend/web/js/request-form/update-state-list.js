/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'jquery',
        'jquery/ui',
        'Magento_Ui/js/modal/modal'
    ],
    function ($) {
        'use strict';

        return function (url) {
        $('#country').on('change', function (event) {
            $.ajax({
                    showLoader: true,
                    url: url,
                    data: 'country_id='+jQuery('#country').val(),
                    type: "GET",
                    dataType: 'json'
                }).done(function (data) {
                    if(data.value=='') {
                    } else {
                        $('#state').closest('.form-control').html(data.value);
                    }
                });
            });
    };
});