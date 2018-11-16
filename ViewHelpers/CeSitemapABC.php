<?php
namespace kupix\qpxviewhelper\ViewHelpers;

class CeSitemapABCViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
   /**
   * Rechner
   * @params integer $uid (CE)
   * @params string $tableCell (of sys_category)
   * @return CategorieName
   * @author Kurt Kunig
   */
   public function initializeArguments() {
      $this->registerArgument('uid', 'integer', 'enthaelt die UID der Seite für den Einstiegspunkt der Sitemap', TRUE);
      $this->registerArgument('tableCell', 'string', 'enthaelt das Tabellenelement aus der Tabelle »pages«', TRUE);
      $this->registerArgument('lang', 'integer', 'die Sprach-ID', TRUE);
   }


   public function render() {

      if ($this->arguments['uid'] === NULL) return 'Keine_ID_uebergeben';

      $uid = $this->arguments['uid'];
      $table = ($this->arguments['table'] == '') ? $tableCell = 'tt_content' : $this->arguments['table'];


      $pages = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
         'uid, pid, title',
         'pages',
         'deleted = 0 AND hidden = 0 AND pid = ' . $uid
      );

      $pageCounter = 0;
      $antwort = '';

      // die nächsten Ebenen untersuchen; sind noch Unterseiten vorhanden?
      if ($pages) {
         $totalpages = array();
         $totalpageTitles = array();
         while($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($pages)) {
            $totalpages[$pageCounter] = $row['uid'];
            $totalpageTitles[$pageCounter] = $row['title'];
            $pageCounter++;
            $this->findPages ($row['uid'], $totalpages, $totalpageTitles, $pageCounter);
         }
      } else {
         return "No_pages_found";
      }

/* ******************************************************** */

      $i = 0;
      $ceCount = 0;
      $totalPageCEs = array();
      $UIDs = array();
      $PIDs = array();

      $where1 = "deleted = 0 AND hidden = 0 AND header_layout < 100"
            . " AND TRIM(header) != ''"
            . " AND header_style < 99"
            . " AND"
            . " ("
            . "     SUBSTRING(header,1,1) >= 'A' "
            . " AND SUBSTRING(header,1,1) <= 'Z' "
            . " OR  SUBSTRING(header,1,1) >= 'a' "
            . " AND SUBSTRING(header,1,1) <= 'z' "
            . ")";

      while($i < $pageCounter) {

         $where = $where1
            . ' AND pid = ' . $totalpages[$i]
            . ' AND sys_language_uid = ' . $this->arguments['lang'];

//print "<p class='midi'>where = ".$where."</p>";

         $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid, pid, header',
            'tt_content',
            $where
         );

         if ($res) {
            while($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
               $row['header'] = ucfirst ($row['header']);
               $totalPageCEs[$ceCount] = $row['header'] . chr(9) . $row['uid'] . chr(9) . $row['pid'] . chr(9) . $totalpageTitles[$i];
//               $totalPageCEs[$ceCount] = $row['header'] . " \ " . $row['uid'] . " \ " . $row['pid'] . " \ " . $totalpageTitles[$i];
               $ceCount++;
            }
         }

         $i++;
      }


      $antwort = '<ul class="letterBox">';
      $i = 0;

      array_multisort ( $totalPageCEs );
//      $firstLetter = substr($totalPageCEs[0], 0, 1);
      $firstLetter = mb_substr($totalPageCEs[0], 0, 1,'UTF-8');
      $antwort .= '<span class="alphaIndex">' . $firstLetter . '</span>';
//print "<h2>ceCount=$ceCount</h2>";
      while($i < $ceCount) {
//print "<p class='midi'>firstLetter(".$i.") [$firstLetter]= {".mb_substr($totalPageCEs[$i], 0, 1,'UTF-8'). "} von <b>Header</b> »" .$totalPageCEs[$i]. "«</p>";
         if ($firstLetter !== mb_substr($totalPageCEs[$i], 0, 1,'UTF-8')) {
            $firstLetter = mb_substr($totalPageCEs[$i], 0, 1,'UTF-8');
            $antwort .= '<span class="alphaIndex">' . $firstLetter . '</span>';
         }
         $data = explode (chr(9), $totalPageCEs[$i]);

         $antwort .=
            '<li class="linkBox"><a href="/index.php?id='
            . $data[2] . '#' . $data[1]
            . '" title="Link zum Artikel '
            . mb_substr($data[0], 0, 25,'UTF-8') . '">' . $data[0]
            . '</a>'
            . ' <span class="abc_sm">(auf der Seite »'.$data[3].'«)</span></li>';
         $i++;
      }

      $antwort .= '</ul>';

      return $antwort;

   }

// rekursive Prozedur zum Ermitteln aller Unterseiten:
   function findPages ($uid, &$v1, &$tpT, &$pC) {
      $GLOBALS['TYPO3_DB']->sql_free_result($subpages);
      $subpages = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
         'uid, pid, title',
         'pages',
         'deleted = 0 AND hidden = 0'
         . ' AND pid = ' . $uid
      );
      if ($subpages) {
         while($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($subpages)) {
            $v1[$pC] = $row['uid'];
            $tpT[$pC] = $row['title'];
            $pC++;
            $id = $row['uid'];
            $this->findPages ($id, $v1, $tpT, $pC);
         }
      } else {
         return;
      }
   }

}
?>
