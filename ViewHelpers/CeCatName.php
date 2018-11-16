<?php
namespace kupix\qpxviewhelper\ViewHelpers;

use FluidTYPO3\Vhs\ViewHelpers\Asset\AbstractAssetViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;


class CeCatNameViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

   public function initializeArguments() {
      $this->registerArgument('uid', 'integer', 'enthaelt die UID des CE', TRUE);
      $this->registerArgument('tableCell', 'string', 'enthaelt das Tabellenelement aus sys_category', TRUE);
      $this->registerArgument('table', 'string', 'enthaelt den Tabellennamen aus sys_category_record_mm', TRUE);
      $this->registerArgument('catNr', 'integer', 'Abfrage auf category index existing', FALSE);
      $this->registerArgument('contains', 'string', 'category name existing ?', FALSE);
   }

   public function render() {

      $uid = ($this->arguments['uid'] == '') ? 0 : $this->arguments['uid'];
      if ($uid < 1) return $antwort = 'Keine_ID_uebergeben';

      $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", " ");
      $replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "_");

      $catNr     = ($this->arguments['catNr'] == '') ? 0 : $this->arguments['catNr'];
      $tableCell = ($this->arguments['tableCell'] == '') ? 'title' : $this->arguments['tableCell'];
      $table     = ($this->arguments['table'] == '') ? $table = 'tt_content' : $this->arguments['table'];
      $contains  = strtolower(str_replace($search, $replace, $this->arguments['contains']));
      $contains  = preg_replace ( '/[^a-z0-9_-]/i', '', $contains );

//      $antwort = "uid=" . $uid . ' / tableCell=' . $tableCell;
//      return $antwort;

      $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('sys_category');

      $query = $queryBuilder
         ->select($tableCell)
         ->from('sys_category')
         ->join(
            'sys_category',
            'sys_category_record_mm',
            'sys_category_record_mm',
            $queryBuilder->expr()->eq('sys_category_record_mm.uid_local', $queryBuilder->quoteIdentifier('sys_category.uid'))
         )
         ->where($queryBuilder->expr()->eq('sys_category_record_mm.tablenames', '"' . $table .'"'))
         ->andWhere($queryBuilder->expr()->eq('sys_category_record_mm.uid_foreign', $uid))
      ;

//      $antwort = "uid_" . $this->arguments['uid'] . ' tableCell_' . $this->arguments['tableCell'] . ' table_' . $this->arguments['table'];
//      return $antwort;

      $res = $query->execute();
      $antwort = '';
      $i = 0;
      while ($row = $res->fetch()) {
        if ($catNr > 0 || $contains != '') {
            $i++;
            if ($catNr > 0) {
               if ($catNr == $i) {
                  $antwort = strtolower(str_replace($search, $replace, $row[$tableCell]));
                  $antwort = preg_replace ( '/[^a-z0-9_-]/i', '', $antwort );
                  return $antwort;
               }
            }
            if ($contains != '') {
               if ($contains == trim(strtolower(str_replace($search, $replace, $row[$tableCell])))) {
                  return true;
               }
               return false;
            }
        } else {
            $antwort = ' ' .  strtolower(str_replace($search, $replace, $row[$tableCell]));
            $antwort = preg_replace ( '/[^a-z0-9_-]/i', '', $antwort );
        }
     }


      return $antwort;
   }

   function reviseCategory($a) {
      $a  = strtolower(str_replace( ' ', '_', $a));
      $a = str_replace( 'Ä', 'ae', $a);
      $a = str_replace( 'Ö', 'oe', $a);
      $a = str_replace( 'Ü', 'ue', $a);
      $a = str_replace( 'ä', 'ae', $a);
      $a = str_replace( 'ö', 'oe', $a);
      $a = str_replace( 'ü', 'ue', $a);
      $a = str_replace( 'ß', 'ss', $a);
      $a = preg_replace ( '/[^a-z0-9_]/i', '', $a );

      return $a;
   }

}
?>
