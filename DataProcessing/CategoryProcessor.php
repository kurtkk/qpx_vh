<?php
namespace kupix\qpxviewhelper\DataProcessing;

/*
 * This file is part of kupix webdesign www.kupix.de.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Fetch records from the database, using the default .select syntax from TypoScript.
 *
 * This way, e.g. a FLUIDTEMPLATE cObject can iterate over the array of records.
 *
 * Example TypoScript configuration:
 *
 * 10 = TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor
 * 10 {
 *    // Are categories in page or CE available!?
 *    if.isTrue.field = categories
 *    // pidInList = IDs von den Seiten / Verzeichnissen, in denen Kategorien gespeichert sind
 *    pidInList = {$categoriesPids}
 *    tablenames = tt_content
 *    as = contentCategories
 *    // or
 *    //   tablenames = pages
 *    //   as = pageCategories
 * }
 *
 * where "as" means the variable to be containing the result-set from the DB query.
 */
class CategoryProcessor implements DataProcessorInterface
{
   /**
   * @var ContentDataProcessor
   */
   protected $contentDataProcessor;

   /**
   * Constructor
   */
   public function __construct()
   {
      $this->contentDataProcessor = GeneralUtility::makeInstance(ContentDataProcessor::class);
   }

   /**
   * Fetches records from the database as an array
   *
   * @param ContentObjectRenderer $cObj The data of the content element or page
   * @param array $contentObjectConfiguration The configuration of Content Object
   * @param array $processorConfiguration The configuration of this processor
   * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
   *
   * @return array the processed data as key/value store
   */
   public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
   {
      if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
         return $processedData;
      }

      // the table to query
      $tableName = 'sys_category';

      // The variable to be used within the result
      $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'records');

      $processorConfiguration['selectFields'] = 'title';
      $processorConfiguration['recursive'] = '999';
      $processorConfiguration['join'] = 'sys_category_record_mm ON sys_category.uid = sys_category_record_mm.uid_local';

      $getRawData = $cObj->stdWrapValue('getRawData', $processorConfiguration, '0');
      if (isset($processorConfiguration['getRawData'])) {
         unset($processorConfiguration['getRawData']);
      }

      $uid = $processedData[data][uid];
      $tablenames = $cObj->stdWrapValue('tablenames', $processorConfiguration);
      if (empty($tablenames)) {
         return $processedData;
      }
      if (isset($processorConfiguration['tablenames'])) {
         unset($processorConfiguration['tablenames']);
      }

      $processorConfiguration['where'] =
         'sys_category_record_mm.uid_foreign="' . $uid .
         '" AND sys_category_record_mm.tablenames = "' . $tablenames . '"';

      $processorConfiguration['orderBy'] = 'sys_category.sorting';

      // Execute a SQL statement to fetch the records
      $records = $cObj->getRecords($tableName, $processorConfiguration);

      $processedRecordVariables = [];
      foreach ($records as $key => $record) {
         $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
         $recordContentObjectRenderer->start($record, $tableName);
         $processedRecordVariables[$key] = $record;

         if ($getRawData == 0) {
            $catString = strtolower($processedRecordVariables[$key][title]);
            $catString = str_replace( ' ', '_', $catString);
            $catString = str_replace( 'ä', 'ae', $catString);
            $catString = str_replace( 'ö', 'oe', $catString);
            $catString = str_replace( 'ü', 'ue', $catString);
            $catString = str_replace( 'ß', 'ss', $catString);
            $catString = preg_replace ( '/[^a-z0-9_-]/i', '', $catString );
            $catString = str_replace( '__', '_', $catString);
         } else {
            $catString = $processedRecordVariables[$key][title];
         };
         $processedRecordVariables[$key] = $catString;
      }

      $processedData[$targetVariableName] = $processedRecordVariables;

      return $processedData;

   }
}
