<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$_EXTCONF = unserialize($_EXTCONF);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gridelements_backend_layout');

# Änderungen in der Extension "news":
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['fal_related_files']['label'] = 'Zum Thema passende Dokumente:';


# Änderungen in der Extension "gridelements":
#$GLOBALS['TCA']['tx_gridelements_backend_layout']['ctrl']['dividers2tabs'] = 0;
$GLOBALS['TCA']['tx_gridelements_backend_layout']['columns']['horizontal'] = 0;

?>
