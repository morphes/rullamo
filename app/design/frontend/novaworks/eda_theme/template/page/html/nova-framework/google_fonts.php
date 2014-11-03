<?php
function google_fonts() {
$storeId = Mage::app()->getStore()->getStoreId();
$eda_theme_customfont = '';
$default = array(
					'Din Text Pro Regular',
					'arial',
					'verdana',
					'trebuchet',
					'georgia',
					'times',
					'tahoma',
					'helvetica'
				);
$novaworks_fonts = array(
					Mage::getStoreConfig('themeoptions_theming/theme_fonts/body_font', $storeId),
					Mage::getStoreConfig('themeoptions_theming/theme_fonts/heading_font', $storeId)
				);			
foreach($novaworks_fonts as $novaworks_font) {
	
	if(!in_array($novaworks_font, $default)) {

			$eda_theme_customfont = str_replace(' ', '+', $novaworks_font). ':300,300italic,400,400italic,700,700italic,900,900italic|' . $eda_theme_customfont;
	}
}
	if($eda_theme_customfont){	
	 echo '<link rel="stylesheet"  href="http://fonts.googleapis.com/css?family=' . 		substr_replace($eda_theme_customfont ,"",-1) . '" type="text/css" media="screen" />';
	}
if(Mage::getStoreConfig('themeoptions_theming/theme_fonts/body_font', $storeId) == 'Din Text Pro Regular' ||  Mage::getStoreConfig('themeoptions_theming/theme_fonts/heading_font', $storeId) == 'Din Text Pro Regular') {
?>
<!--// FONTDECK LOADER //-->
<script type="text/javascript">
WebFontConfig = { fontdeck: { id: '41599' } };

(function() {
  var wf = document.createElement('script');
  wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
  '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
  wf.type = 'text/javascript';
  wf.async = 'true';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(wf, s);
})();
</script>
<?php	
}
}
?>