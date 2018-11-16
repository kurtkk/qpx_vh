<?php
declare(strict_types=1);

namespace kupix\qpxviewhelper\ViewHelpers;

class StringReplaceViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

   public function initializeArguments() {
      $this->registerArgument('s', 'string', 'die zu untersuchende Zeichenkette', TRUE);
      $this->registerArgument('searchFor', 'string', 'was soll ersetzt werden', TRUE);
      $this->registerArgument('replaceWith', 'string', 'zu ersetzen mit');
      $this->registerArgument('noSpan', 'boolean', 'kein span class="hline2" einfügen', FALSE);
      $this->registerArgument('noSpecificSigns', 'boolean', 'alle Sonderzeichen löschen', FALSE);
   }

	public function render() {

      if ($this->arguments['s'] == '') return 'Error: kein String / Suche nach »' . $this->arguments['searchFor'] . '«';
      $s = $this->arguments['s'];
      $n = $this->arguments['searchFor'];
      $rpl = $this->arguments['replaceWith'];
      $noSpan = $this->arguments['noSpan'];
      $noSpecificSigns = $this->arguments['noSpecificSigns'];

      if ($n == 'chr(10)') {
         $cr0A = chr(10);
         $cr0D = chr(13);
         if ($noSpan) {
            $s = str_replace($cr0A, $rpl, $s);
         } else {
            $treffer = substr_count ( $s, $cr0A );
            if ($treffer > 0) {
               $i = strpos($s, $cr0A);
               $s = substr_replace ( $s, '<span class="hline2">', $i, 1);
               $s = str_replace($cr0D, '', $s);
               $s = str_replace($cr0A, $rpl, $s);
               $s .= '</span>';
            }
         }
         if ($noSpecificSigns) {
            return preg_replace("/[^a-z0-9<>äöüÄÖÜß!?]+/i", " ", $s);
         }

         return $s;
      }

		return trim(str_replace($n, $rpl, $s));
	}
}
?>
