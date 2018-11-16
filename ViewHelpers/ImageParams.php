<?php
namespace kupix\qpxviewhelper\ViewHelpers;

class ImageParamsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
   /**
   * Rechner
   * @param string $image, string $orientation
   * @return image $orientation='h' => height and $orientation='w' => width
   * @author Kurt Kunig
   */
   public function initializeArguments() {
      $this->registerArgument('image', 'string', 'enthaelt den Bild-Pfad', TRUE);
      $this->registerArgument('orient', 'string', 'enthaelt h oder w Bild-Hoehe oder -Breite', TRUE);
   }

   public function render() {

      if ($this->arguments['image'] === NULL) return '';

      $imageParams = getimagesize($this->arguments['image']);

      // [0] = width, [1] = height
      if ($this->arguments['orient'] == "w") return $imageParams[0];
      if ($this->arguments['orient'] == "h") return $imageParams[1];

      return '';

   }
}
?>
