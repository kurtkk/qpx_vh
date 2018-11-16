<?php
namespace kupix\qpxviewhelper\ViewHelpers;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Creates a Link for editing in BE
 *
 * @package THREEME\Threeme\ViewHelpers
 */
class EditLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * @var boolean
     */
    protected $doEdit = 1;

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * returning a EditLink-Tag for TYPO3 Backend
     * @param array $element
     * @param string $style
     * @param string $table
     * @return mixed
     */

   public function initializeArguments() {
      $this->registerArgument('element', 'string', 'Mask-Datenelement', TRUE);
      $this->registerArgument('style', 'string', 'style z.B. color:', FALSE);
      $this->registerArgument('table', 'string', 'aus welcher Tabelle [def. tt_content]', FALSE);
   }

    public function render() {
        $element  = $this->arguments['element'];
        $style    = $this->arguments['style'];
        $table    = ($this->arguments['table'] == '') ? $table = 'tt_content' : $this->arguments['table'];

        if ($this->doEdit && $this->getBackendUser()->recordEditAccessInternals($table, $element)) {
            $urlParameters = [
                'edit' => [
                    $table => [
                        $element['uid'] => 'edit'
                    ]
                ],
                'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI')
            ];
            $uri = BackendUtility::getModuleUrl('record_edit', $urlParameters);

            $this->tag->addAttribute('href', $uri);
            if ($style != '') $this->tag->addAttribute('style', $style);
        }

        $this->tag->setContent($this->renderChildren());
        $this->tag->forceClosingTag(TRUE);

        return $this->tag->render();
    }
}
