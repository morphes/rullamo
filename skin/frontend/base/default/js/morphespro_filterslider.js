/**
 * @category    Morphes
 * @package     MorphesPro_FilterSlider
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */
;var MorphesPro = MorphesPro || {};
MorphesPro.filterSlider = function(id, o) {
	var s = new Control.PriceSlider([id + '-from', id + '-to'], id + '-track', {
		spans: [id + '-span'], 
		restricted: true,
		range: $R(o.rangeFrom, o.rangeTo),
		sliderValue: [o.appliedFrom, o.appliedTo]
	});
	
	s.options.onSlide = function(value) {
		var formattedValue = [o.numberFormat.replace('0', value[0].round()+''), o.numberFormat.replace('0', value[1].round()+'')];
		$(id + '-applied').update(o.appliedFormat.replace("__0__", formattedValue[0]).replace("__1__", formattedValue[1]));
	};
	s.options.onChange = function(value) {
		if (value[0] <= o.rangeFrom && value[1] >= o.rangeTo) {
			window.setLocation(o.clearUrl);
		}
		else {
			var formattedValue = [value[0].round(), value[1].round()];
			window.setLocation(o.url.replace("__0__", formattedValue[0]).replace("__1__", formattedValue[1]));
		}
	};
};