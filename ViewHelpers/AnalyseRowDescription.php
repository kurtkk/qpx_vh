<?php
namespace kupix\qpxviewhelper\ViewHelpers;

class AnalyseRowDescriptionViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
   /**
   * Rechner
   * @params integer $uid (CE)
   * @params string $tableCell (of sys_category)
   * @return CategorieName
   * @author Kurt Kunig
   */
   public function initializeArguments() {
      $this->registerArgument('uid', 'integer', 'enthaelt die UID des CE', TRUE);
      $this->registerArgument('operator', 'string', 'beispiel: "==" oder "*=" oder "=*"', TRUE);
      $this->registerArgument('searchFor', 'string', 'was soll ersetzt werden', TRUE);
   }


   public function render() {

      $uid = intval($this->arguments['uid']);
      if ($uid <= 0) return 'Missing_Content-ID_uid';
/*
print "<p class='midi'> Liste der Seiten";
print_r ($this->arguments['uid']);
print "</p>";
return 'Liste der Seiten: ' . $listOfPages;
*/
      $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
         'rowDescription',
         'tt_content',
         'deleted = 0 AND hidden = 0'
         . ' AND uid = ' . $uid
      );

      $antwort = '';

      if (!$res) {
        $antwort = "Da_ging_was_schief_bei_" . $table;
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        return $antwort;
      }

      $searchFor = trim($this->arguments['searchFor']);
      $searchForLength = strlen ($searchFor);
      $operator = $this->arguments['operator'];

      while($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
         $rowDescription = trim($row['rowDescription']);
         $rowDescriptionLength = strlen ($rowDescriptionLength);

         $s = strpos ($rowDescription, $searchFor);
         if ( !$s ) return '';

//       Wenn der gesuchte String vorh. ist, mit Operatoren verknüpfen
         switch ( $operator )
         {
            case '==':
              $endPos = $s + $searchForLength;
              if ( $s > 0 AND substr($rowDescription, $s - 1, 1) != ' ' )                             return '';
              if ( $endPos < $rowDescriptionLength AND substr($rowDescription, $endPos, 1) != ' ' )   return '';
              if ( $s == 0 AND substr($rowDescription, $endPos, 1) == ' ' )                           return $searchFor;
              if ( $endPos == $rowDescriptionLength AND substr($rowDescription, $s - 1, 1) == ' ' )   return $searchFor;

              $antwort .=  $searchFor . ' steht NICHT frei in';
            break;
            case '*=':
              if ( (substr($rowDescription, $s - 1, 1) == ' ' ) && (substr($rowDescription, $s + $searchForLength, 1) != ' ' ) ) return '';
              $antwort .=  $searchFor;
            break;
            case '=*':
              if ( (substr($rowDescription, $s - 1, 1) != ' ' ) && (substr($rowDescription, $s + $searchForLength, 1) == ' ' ) ) return '';
              $antwort .=  $searchFor;
            break;
            case '*=*':
              if ( $s ) return $searchFor;
              $antwort .=  '';
            break;
            default:
             $antwort .=  '$operator ungültig';
            break;
         }


//         substr ( string $string, int $start [, int $length] )


         $antwort .= ' »' . $row['rowDescription'] . '«';
      }

      $GLOBALS['TYPO3_DB']->sql_free_result($res);



      return $antwort;

   }

}
?>
