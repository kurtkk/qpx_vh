<?php
namespace kupix\qpxviewhelper\ViewHelpers;

use FluidTYPO3\Vhs\ViewHelpers\Asset\AbstractAssetViewHelper;

class StringContainsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
   public function initializeArguments() {
      $this->registerArgument('haystack', 'string', 'enthaelt zu untersuchenden String', TRUE);
      $this->registerArgument('needle', 'string', 'enthaelt den Teilstring, nach dem gesucht wird', TRUE);
   }

   public function render() {
      $haystack = trim($this->arguments['haystack']);
      if ($haystack == '') return 'missing haystack!?';

      $needle = $this->arguments['needle'];
      if ($needle == '') return 'what shall I seach for? Missing needle';

      return stristr ( $haystack, $needle );
    }
}
?>
