<?php
namespace kupix\qpxviewhelper\ViewHelpers;

class PageCatNameViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
   /**
   * Rechner
   * @param string $uid (CE)
   * @return CategorieName
   * @author Kurt Kunig
   */
   public function initializeArguments() {
      $this->registerArgument('uid', 'string', 'enthaelt die UID der Seite', TRUE);
   }

   public function render() {

      if ($this->arguments['uid'] === NULL) return 'Keine ID übergeben!?';

		$query = 'SELECT title FROM sys_category'
         . ' join sys_category_record_mm ON(sys_category_record_mm.uid_local = sys_category.uid)'
         . ' WHERE deleted = 0 AND hidden = 0'
         . " AND sys_category_record_mm.tablenames='pages'"
         . " AND sys_category_record_mm.uid_foreign = " . $this->arguments['uid']
         ;

      $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
         'title',
         'sys_category
         INNER JOIN sys_category_record_mm ON(sys_category_record_mm.uid_local = sys_category.uid)',
         'deleted = 0 AND hidden = 0'
         . ' AND sys_category_record_mm.tablenames="pages"'
         . ' AND sys_category_record_mm.uid_foreign = ' . $this->arguments['uid'] ,
         $groupBy='',
         $orderBy= '',
         $limit='1'
      );

      $antwort = '';

      if ($res) {
        while($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
          $antwort .= ' ' . strtolower(str_replace( ' ', '_', $row['title']));
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
      } else {
        $antwort = "Da ging was schief...";
      }

      return $antwort;

   }
}
?>
