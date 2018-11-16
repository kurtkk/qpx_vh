<?php
namespace kupix\qpxviewhelper\ViewHelpers;

// use TYPO3\CMS\Core\Utility\MathUtility;

class DateDiffViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
   /**
   * Rechner
   * @params DateTime $date1
   * @params DateTime $date2
   * @params boolean $return_format
   * @return mixed $return_data
   * @author Kurt Kunig
   */
   public function initializeArguments() {
      $this->registerArgument('date1', 'DateTime', '', TRUE);
      $this->registerArgument('date2', 'DateTime', '', TRUE);
      $this->registerArgument('return_format', 'boolean', 'format: false=Differenz, true=Dauer', FALSE);
   }


   public function render() {
      $date1 = $this->arguments['date1'];
      $date2 = $this->arguments['date2'];
      $return_format = trim($this->arguments['return_format']);

      if (!$date1) return 'missing $date1!?';
      if (!$date2) return 'missing $date2!?';
      if ($return_format) {
         $return_data = intval(date_diff($date1, $date2) -> format('%a')) + 1;
      } else {
         $return_data = date_diff($date1, $date2) -> format('%a');
      }

      return $return_data;
    }
}
?>
