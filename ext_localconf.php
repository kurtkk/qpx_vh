<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$_EXTCONF = unserialize($_EXTCONF);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
   \TYPO3\CMS\Core\Imaging\IconRegistry::class
);
$iconRegistry->registerIcon(
   'menu_icon_sitemap', // Icon-Identifier, z.B. tx-myext-action-preview
   \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
   ['source' => 'EXT:qpxviewhelper/Resources/Public/Icons/ContentElements/menu_icon_sitemap.svg']
);

// register the yaml files for the new RTE-Editor „rte_ckeditor“ in TYPO3 V8
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['qpx'] = 'EXT:qpxviewhelper/Configuration/RTE/Qpx.yaml';
//$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['custom1'] = 'EXT:template_site/Configuration/PageTs/Rte/Custom1.yaml';

$GLOBALS['TBE_STYLES']['skins'][$_EXTKEY] = array(
   'name' => $_EXTKEY,
   'stylesheetDirectories' => array(
      'structure' => 'EXT:' . $_EXTKEY . '/Resources/Public/Css/Backend/'
   )
);

?>

