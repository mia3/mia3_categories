<?php
namespace Mia3\Mia3Categories\Domain\Model;

/*
 * This file is part of the TYPO3 CMS project.
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
use Famelo\FameloCommon\ObjectStorageSorter;

/**
 * This model represents a category (for anything).
 *
 * @api
 */
class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category
{
    /**
     * @var integer
     */
    protected $sorting;

    /**
     * categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mia3\Mia3Categories\Domain\Model\Category>
     * @lazy
     */
    protected $children = null;

    /**
     * @var \Mia3\Mia3Categories\Domain\Model\Category|NULL
     * @lazy
     */
    protected $parent = null;

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Returns the sorting
     *
     * @return integer $sorting
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Sets the sorting
     *
     * @param integer $sorting
     * @return void
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mia3\Mia3Categories\Domain\Model\Category> $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mia3\Mia3Categories\Domain\Model\Category>
     */
    public function getChildren()
    {
        return ObjectStorageSorter::sort($this->children, 'sorting');
    }

    public function getChildrenRecursive($children = array())
    {
        foreach ($this->children as $child) {
            $children[] = $child;
            $children = $child->getChildrenRecursive($children);
        }

        return $children;
    }

    public function getHasPages()
    {
        $children = $this->getChildrenRecursive();
        $uids = array($this->getUid());
        foreach($children as $child) {
            $uids[] = $child->getUid();
        }
        $where = '  tablenames = "pages"
                    AND fieldname = "categories"
                    AND uid_local IN (' . implode(',', $uids) . ')';
        $result = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows('uid_local', 'sys_category_record_mm', $where);
        return $result > 0;
    }
}