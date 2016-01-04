<?php
namespace Mia3\Mia3Categories\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Marc Neuhaus <mneuhaus@famelo.com>, Famelo
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * FooController
 */
class CategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @return void
     */
    public function indexAction() {
        $where = '1=!';
        if (isset($_GET['id'])) {
            $where = 'pid = ' . $_GET['id'];
        }
        $categories = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'sys_category',
			$where . ' AND deleted = 0' . BackendUtility::BEenableFields('sys_category'),
            '',
            'sorting'
		);
        var_dump($where . BackendUtility::BEenableFields('sys_category'));
        exit();
        $categories = $this->createNestedSet($categories);
        $this->view->assign('categories', $categories);
        $this->view->assign('token', $_GET['moduleToken']);
        $this->view->assign('returnUrl', urlencode($_SERVER['REQUEST_URI']));
        $this->view->assign('baseUrl', urlencode($_SERVER['SCRIPT_NAME']));
    }

    public function createNestedSet($rows, $parent = array('uid' => 0)) {
        $children = array();
        foreach ($rows as $key => $row) {
            if ($row['parent'] == $parent['uid']) {
                $row['children'] = $this->createNestedSet($rows, $row);
                $children[] = $row;
            }
        }
        return $children;
    }

    /**
     * @return void
     */
    public function updateSortingAction() {
        if ($this->request->hasArgument('positions')) {
            $positions = array();
            foreach ($this->request->getArgument('positions') as $uid => $row) {
                $GLOBALS['TYPO3_DB']->exec_UPDATEquery('sys_category', 'uid = ' . intval($uid), array(
                    'sorting' => intval($row['sorting']),
                    'parent' => intval($row['parent'])
                ));
            }
        }
        return '1';
    }

    /**
     * @return void
     */
    public function batchCreateAction() {
        $categories = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'sys_category',
			'1=1' . BackendUtility::BEenableFields('sys_category'),
            '',
            'sorting',
            '',
            'uid'
		);
        foreach ($categories as $key => $category) {
            $level = 0;
            while ($category['parent'] > 0) {
                $category = $categories[$category['parent']];
                $level++;
            }
            $categories[$key]['title'] = str_repeat('- ', $level) . $categories[$key]['title'];
        }
        $this->view->assign('categories', $categories);

        if ($this->request->hasArgument('categories')) {
            $parents = array();
            if (!empty($this->request->getArgument('parent'))) {
                $parents[] = $this->request->getArgument('parent');
            }

            $categories = explode(chr(10), $this->request->getArgument('categories'));

            foreach ($categories as $category) {
                $category = rtrim($category);
                $depth = strlen($category) - strlen(ltrim($category, "\t"));
                $category = trim($category);

                $data = array(
                    'pid' => intval($_GET['id']),
                    'title' => $category,
                    'tstamp' => time(),
                    'crdate' => time(),
                    'parent' => isset($parents[$depth]) ? $parents[$depth] : 0
                );
                $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_category', $data);
                $parents[$depth + 1] = $GLOBALS['TYPO3_DB']->sql_insert_id();
            }

            $this->redirect('index');
        }
    }

}
