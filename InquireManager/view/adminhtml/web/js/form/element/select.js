/**
 * 
 */
define([
	'Magento_Ui/js/form/element/select'
], function (Select) {
	'use strict';
	
	return Select.extend({
		filter: function (value, field) {
            var source = this.initialOptions,
                result;

            field = field || this.filterBy.field;

            result = _.filter(source, function (item) {
                return item[field] == value || item.value === '';
            });

            this.setOptions(result);
        },
	});
});