<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Mia3.' . $_EXTKEY,
        'web',          // Main area
        'mod1',         // Name of the module
        '',             // Position of the module
        array(          // Allowed controller action combinations
            'Category' => 'index,updateSorting'
        ),
        array(          // Additional configuration
            'access'    => 'user,group',
            'icon'      => 'EXT:mia3_categories/ext_icon.gif',
            'labels'    => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
        )
    );
}
