<?php

$temporaryColumns = array(
    'sorting' => array(
        'exclude' => 1,
        'label' => 'Sortierung',
        'config' => array(
            'type' => 'passthrough'
        ),
    ),
    'children' => array(
        'exclude' => 1,
        'label' => 'Unterkategorien',
        'config' => array(
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'sys_category',
            'foreign_field' => 'parent',
            'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.sorting',
            'size' => 10,
            'autoSizeMax' => 20,
            'minitems' => 0,
            'maxitems' => 20
        ),
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category', $temporaryColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category','sorting,children');
