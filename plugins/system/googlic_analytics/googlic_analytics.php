<?php
/** 
*	GoogliC Analytics
*-----------------------------------------------------------------------------------------
*   @version	$version 1.2.3 JoomliC 2012-05-20$
*   @copyright	Copyright (C) 2012 JoomliC. Tous droits réservés / All rights reserved.
*/
/**
*   Displays 	<a href="http://www.gnu.org/licenses/gpl-2.0.html">GNU/GPL License</a>
*	@license	GNU General Public License version 2 or later; see LICENSE.txt
*/
/**
*	Displays as JoomliC<strong>info@joomlic.com</strong>
*	, where underlined text is a "mailto:info@joomlic.com" link
*	@author		JoomliC <info@joomlic.com>
*	Updated		19th May 2012
*/
/**
*	Displays <a href="http://www.joomlic.com">www.joomlic.com</a>
*	@link		http://www.joomlic.com
*-----------------------------------------------------------------------------------------
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport(' joomla.plugin.plugin' );
jimport( 'joomla.document.document' );


class plgSystemGOOGLIC_analytics extends JPlugin
{
	private static $filtreSuivi = false;

	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();

		if (($app->isSite()) && ($doc->getMimeEncoding() == 'text/html')) {
			$groups = (array) $this->params->get('groupes-exclus', null);

			if (!empty($groups)) {
				$userGroups = JFactory::getUser()->get('groups');
				if (!array_intersect($groups, $userGroups)) {
					self::_addTracking();
					self::$filtreSuivi = true;
				}
			} else {
				self::_addTracking();
				self::$filtreSuivi = true;
			}
		}		
	}

	public static function okfiltreSuivi()
	{
		return self::$filtreSuivi;
	}

	private function _addTracking()
	{
		$doc = JFactory::getDocument();
		$option = $this->params->get('option');
		$hostname = $_SERVER['SERVER_NAME']; 
		$hostname = str_replace('www.', '', $hostname); 

		
				$script = "
	// GoogliC Analytics v1.2.3 - plugin joomla - http://www.joomlic.com
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '".htmlspecialchars($this->params->get('idgoogle'))."']);";
		if ($option == 1) {
			$script .= "
	_gaq.push(['_setDomainName', '".htmlspecialchars($hostname)."']);";
		}
		if ($option == 2) {
			$script .= "
	_gaq.push(['_setDomainName', '".htmlspecialchars($hostname)."']);
	_gaq.push(['_setAllowLinker', true]);";
		}
		
		$script .= "
	_gaq.push(['_trackPageview']);\n";
		
		
		$script .= " (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();";


		$doc->addScriptDeclaration($script, $type= 'text/javascript');
	}	
}


