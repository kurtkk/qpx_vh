<?php
namespace kupix\qpxviewhelper\ViewHelpers;

class SetPrintLinksInfoViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
   /**
   * Rechner
   * @param string $image, string $orientation
   * @return image $orientation='h' => height and $orientation='w' => width
   * @author Kurt Kunig
   */
   public function initializeArguments() {
      $this->registerArgument('formattedBodytext', 'string', 'enthaelt den kompletten Content eines CE', TRUE);
//      $this->registerArgument('orient', 'string', 'enthaelt h oder w Bild-Hoehe oder -Breite', TRUE);
   }

   public function render() {

      if ($this->arguments['formattedBodytext'] === '') return 'es wurde kein CE-Inhalt übergeben';

      $links = array();
      $i = 0;

      $content = $this->arguments['formattedBodytext'];
      $content_mod = $content;

//return $content_mod;

      $laenge = strlen ( $content );

      $p = strpos ( $content, 'href=' );

      $antwort = '';

      while ( $p ) {
         $links[$i] = $p + 6;
         $j = strpos ( $content, '"', $links[$i] );

         $linkLength = $j - $links[$i];

         $sss = substr($content, $links[$i], $linkLength);

         $ip = $i + 1;
         $antwort .= '<li><sup>*' . $ip . '</sup> [' . $sss . ']</li>';

         $p = strpos ( $content, 'href=', $links[$i] );
         $i++;
      }

      $i = 0;
      $p = strpos ( $content_mod, '</a>' );
      while ( $p ) {
         $j = $p + 4;
         $ip = $i + 1;
         $repl = '<sup>*' . $ip . '</sup> ';
         $content_mod = substr_replace ( $content_mod, $repl, $j, 1 );
         $p = strpos ( $content_mod, '</a>', $j );
         $i++;
      }

      return $content_mod
             . '<div class="link-referenz"><p>Fußnoten zu den Links:</p><ul>'
             . $antwort
             . '</ul></div>'
      ;

   }
}
?>
