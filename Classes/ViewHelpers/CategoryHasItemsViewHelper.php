<?php
namespace Mia3\Mia3Categories\ViewHelpers;

/*
 * This file is part of the FluidTYPO3/Flux project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Mia3\Mia3Categories\Domain\Model\Category;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 *
 */
class CategoryHasItemsViewHelper extends AbstractViewHelper {

    /**
     * @param mixed $categories
     * @param string $tableName
     * @param string $fieldName
     */
    public function render($categories, $tableName = "pages", $fieldName = "categories") {
        if (!is_array($categories)) {
            $categories = explode(',', $categories);
        }
        $categoryUids = array();
        foreach ($categories as $key => $category) {
            if ($category instanceof Category) {
                $groupCategories = array($category->getUid());
                foreach ($category->getChildrenRecursive() as $childCategory) {
                    $groupCategories[] = $childCategory->getUid();
                }
                $categoryUids[] = $groupCategories;
            } else {
                $categoryUids[] = explode(',', $category);
            }
        }

        $from = array($tableName);
        $where = array();
        foreach($categoryUids as $key => $categoryGroup) {
            $mmTableName = 'mm' . $key;
            $foreignTableName = $tableName;
            $where[] =  $mmTableName . '.tablenames = "' . $tableName . '"
                    AND ' . $mmTableName . '.fieldname = "' . $fieldName . '" 
                    AND ' . $mmTableName . '.uid_local IN (' . implode(',', $categoryGroup) . ')
                    AND ' . $mmTableName . '.uid_foreign = ' . $foreignTableName . '.uid
                    ';
            $from[] = 'sys_category_record_mm as ' . $mmTableName;
        }
//        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
        $result = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows('*', implode(', ', $from), implode(' AND ', $where) . $GLOBALS["TSFE"]->sys_page->enableFields('pages'));
//        echo $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;
//        exit();
        return $result > 0;
    }

}
