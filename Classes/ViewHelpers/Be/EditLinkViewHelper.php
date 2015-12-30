<?php
namespace Mia3\Mia3Categories\ViewHelpers\Be;

/*
 * This file is part of the FluidTYPO3/Flux project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 *
 */
class EditLinkViewHelper extends AbstractTagBasedViewHelper {
	/**
     * name of the tag to be created by this view helper
     *
     * @var string
     * @api
     */
    protected $tagName = 'a';

	/**
	 * @param string $uid
	 * @param string $class
	 */
	public function render($uid, $class = '') {
		$uri = BackendUtility::getModuleUrl('record_edit', array(
			'edit' => array(
				'sys_category' => array(
					$uid => 'edit'
				)
			),
			'returnUrl' => str_replace('/typo3/', '', $_SERVER['REQUEST_URI'])
		));

		$this->tag->addAttribute('href', $uri);
		$this->tag->addAttribute('class', $class);
		$this->tag->setContent($this->renderChildren());
		$this->tag->forceClosingTag(TRUE);
		return $this->tag->render();
	}

}
