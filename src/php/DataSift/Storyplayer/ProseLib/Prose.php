<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer/ProseLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * base class for all Prose classes
 *
 * @category  Libraries
 * @package   Storyplayer/ProseLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Prose
{
	protected $st = null;
	protected $args = array();
	protected $topElement = null;
	protected $topXpath   = null;

	public function __construct(StoryTeller $st, $args = array())
	{
		// save the StoryTeller object; we're going to need it!
		$this->st = $st;

		// save any arguments that have been passed into the constructor
		// our child classes may be interested in them
		if (!is_array($args)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$this->args = $args;

		// setup the page context
		$this->initPageContext();

		// run any context-specific setup that we need
		$this->initActions();
	}

	protected function initPageContext()
	{
		// shorthand
		$st = $this->st;

		// make sure we are looking at the right part of the page
		$pageContext = $st->getPageContext();
		$pageContext->switchToContext($st);
	}

	/**
	 * override this method if required (for example, for web browsers)
	 *
	 * @return void
	 */
	protected function initActions()
	{
	}

	protected function initBrowser()
	{
		// do we have a web browser?
		$browser = $this->st->getRunningWebBrowser();

		// set our top XPATH node
		$this->setTopXpath("//html");

		// set our top element
		$topElement = $browser->getElement('xpath', '/html');
		$this->setTopElement($topElement);
	}

	public function __call($methodName, $params)
	{
		// this only gets called if there's no matching method
		throw new E5xx_NotImplemented(get_class($this) . '::' . $methodName);
	}

	public function getTopElement()
	{
		return $this->topElement;
	}

	public function setTopElement($element)
	{
		$this->topElement = $element;
	}

	protected function getTopXpath()
	{
		return $this->topXpath;
	}

	protected function setTopXpath($xpath)
	{
		$this->topXpath = $xpath;
	}

	// ====================================================================
	//
	// Convertors go here
	//
	// --------------------------------------------------------------------

	public function toNum($string)
	{
		$final = str_replace(array(',', '$', ' '), '', $string);

		return (double)$final;
	}
}