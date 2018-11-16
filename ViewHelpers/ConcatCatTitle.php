<?php
namespace kupix\qpxviewhelper\ViewHelpers;

use FluidTYPO3\Vhs\ViewHelpers\Asset\AbstractAssetViewHelper;

class ConcatCatTitleViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
// {qpx:concatenateTitle(catTitle:'{contentCategory.data.title}')} oder
// {qpx:concatenateTitle(catTitle:'{pageCategory.data.title}')}

   public function initializeArguments() {
      $this->registerArgument('catTitle', 'string', 'enthaelt die rohe Kategoriebezeichnung (title) aus sys_category', TRUE);
   }

   public function render() {

      if ($this->arguments['catTitle'] == '') return $antwort = 'Kein_CatTitle_uebergeben';

      $catTitle  = strtolower($this->arguments['catTitle']);

      $antwort = $catTitle;
      $antwort = str_replace( ' ', '_', $antwort);
      $antwort = str_replace( 'Ä', 'Ae', $antwort);
      $antwort = str_replace( 'Ö', 'Oe', $antwort);
      $antwort = str_replace( 'Ü', 'Ue', $antwort);
      $antwort = str_replace( 'ä', 'ae', $antwort);
      $antwort = str_replace( 'ö', 'oe', $antwort);
      $antwort = str_replace( 'ü', 'ue', $antwort);
      $antwort = str_replace( 'ß', 'ss', $antwort);
      $antwort = preg_replace ( '/[^a-z0-9_-]/i', '', $antwort );

      return $antwort;

   }
}
?>
