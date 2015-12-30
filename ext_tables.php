<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Mia3.' . $_EXTKEY,
        'web',          // Main area
        'txmia3categoriesmod1',         // Name of the module
        '',             // Position of the module
        array(          // Allowed controller action combinations
            'Category' => 'index,updateSorting,batchCreate'
        ),
        array(          // Additional configuration
            'access'    => 'user,group',
            'icon'      => 'EXT:core/Resources/Public/Icons/T3Icons/mimetypes/mimetypes-x-sys_category.svg',
            'labels'    => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
        )
    );
}
