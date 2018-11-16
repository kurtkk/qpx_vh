<?php
namespace kupix\qpxviewhelper\ViewHelpers;

use FluidTYPO3\Vhs\ViewHelpers\Asset\AbstractAssetViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class GeLayoutImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

   public function initializeArguments() {
      $this->registerArgument('uid', 'integer', 'enthaelt die UID des CE', TRUE);
      $this->registerArgument('what', 'string', 'was soll ermittelt werden', TRUE);
      $this->registerArgument('tablenames', 'string', 'welche Tabelle', FALSE);
      $this->registerArgument('fieldname', 'string', 'welche Tabelle', FALSE);
      $this->registerArgument('sorting', 'integer', '1. oder 2. Bild z.B.', FALSE);
      $this->registerArgument('sfruid', 'integer', 'sys_file_reference UID', FALSE);
//    1. 'identifier' (von sys_file) => Pfad zum Gridlayout-Image
//    2. 'title'      (von sys_file_reference)
//    3. 'description' (von sys_file_reference)
   }

   public function render() {

      $uid = ($this->arguments['uid'] == '') ? 0 : $this->arguments['uid'];
      if ($uid < 1) return $antwort = 'Keine_ID_uebergeben';

      $tablenames = $this->arguments['tablenames'];
      if ($tablenames === NULL) $tablenames = "tt_content";

      $fieldname = $this->arguments['fieldname'];
      if ($fieldname === NULL) $fieldname = "media";


//      $antwort = "uid_" . $this->arguments['uid'] . ' tableCell_' . $this->arguments['tableCell'];
//      return $antwort;

      if ($this->arguments['uid'] === NULL) return $antwort = 'Keine ID übergeben!?';

      $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('sys_file');

      $where = $queryBuilder->expr()->eq('sys_file_reference.tablenames', '"' . $tablenames . '"')
         . " AND " . $queryBuilder->expr()->eq('sys_file_reference.fieldname', '"' . $fieldname . '"')
         . " AND " . $queryBuilder->expr()->eq('sys_file_reference.uid_foreign', $uid)
      ;

      $pSorting = $this->arguments['sorting'];
      $pSfruid  = $this->arguments['sfruid'];

      $sorting = ($this->arguments['sorting'] === NULL) ? '' : " 'AND' . $queryBuilder->expr()->eq('sys_file_reference.sorting_foreign', $pSorting)";
      $sfruid =  ($this->arguments['sfruid']  === NULL) ? '' : " 'AND' . $queryBuilder->expr()->eq('sys_file_reference.uid', $pSfruid)";

      if ($this->arguments['what'] == 'identifier') {
         $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('sys_file');
         $query = $queryBuilder
            ->select('identifier AS returnValue')
            ->from('sys_file')
            ->join(
               'sys_file',
               'sys_file_reference',
               'sys_file_reference',
               $queryBuilder->expr()->eq('sys_file_reference.uid_local', $queryBuilder->quoteIdentifier('sys_file.uid'))
            )
            ->where($where . $sorting . $sfruid)
            ->setMaxResults('1')
         ;
      } else {
         $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
         $query = $queryBuilder
            ->select($this->arguments['what'] . ' AS returnValue')
            ->from(sys_file_reference)
            ->where($where . $sorting . $sfruid)
            ->setMaxResults('1')
         ;
      }

      $antwort = '';

      $res = $query->execute();
      while ($row = $res->fetch()) {
         $antwort .= strval($row['returnValue']);
      }

      return $antwort;

   }
}
?>
