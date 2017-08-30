<?php
namespace kupix\qpxviewhelper\ViewHelpers;

class CeCatNameViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
   /**
   * Rechner
   * @params integer $uid (CE)
   * @params string $tableCell (of sys_category)
   * @return CategorieName
   * @author Kurt Kunig
   */
   public function initializeArguments() {
      $this->registerArgument('uid', 'integer', 'enthaelt die UID des CE', TRUE);
      $this->registerArgument('tableCell', 'string', 'enthaelt das Tabellenelement aus sys_category', TRUE);
      $this->registerArgument('table', 'string', 'enthaelt den Tabellennamen aus sys_category_record_mm', TRUE);
   }

   public function render() {

//      $antwort = "uid_" . $this->arguments['uid'] . ' tableCell_' . $this->arguments['tableCell'];
//      return $antwort;

      if ($this->arguments['uid'] === NULL) return $antwort = 'Keine_ID_uebergeben';
      $tableCell = ($this->arguments['tableCell'] == '') ? $tableCell = 'title' : $this->arguments['tableCell'];
      $table     = ($this->arguments['table'] == '') ? $table = 'tt_content' : $this->arguments['table'];

//      $antwort = "uid_" . $this->arguments['uid'] . ' tableCell_' . $this->arguments['tableCell'] . ' table_' . $this->arguments['table'];
//      return $antwort;

      $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
         $tableCell,
         'sys_category
         INNER JOIN sys_category_record_mm ON(sys_category_record_mm.uid_local = sys_category.uid)',
         'deleted = 0 AND hidden = 0'
         . ' AND sys_category_record_mm.tablenames="' . $table . '"'
         . ' AND sys_category_record_mm.uid_foreign = ' . $this->arguments['uid'] ,
         $groupBy='',
         $orderBy= ''
      );

      $antwort = '';

      if ($res) {
        while($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
          $antwort .= ' ' . strtolower(str_replace( ' ', '_', $row[$tableCell]));
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
      } else {
        $antwort = "Da_ging_was_schief_bei_" . $table;
      }

      return $antwort;

   }
}
?>
