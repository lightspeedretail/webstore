<?php


class CustomPageUrlRule extends CBaseUrlRule
{
	public $connectionID = 'db';

	public function createUrl($manager,$route,$params,$ampersand)
	{

		if ($route=="site/index") {

				$ct=0;
				$retString = "";
				foreach ($params as $key=>$val)
					$retString .= ($ct++ > 0 ? $ampersand : "?").$key."=".$val;

				return $retString;
		}

		if ($route==='search/browse')
		{
			//This route may be a category view or a searchfield search
			$ct=0;
			if (isset($params['cat'])) {
				$retString = $params['cat'];
				unset($params['cat']);
			} elseif (isset($params['search/browse']))
				$retString = "search/browse";
			else $retString = 'search/browse';

			foreach ($params as $key=>$val)
				if ($val != 'cat' && $val != '')
					$retString .= ($ct++ > 0 ? $ampersand : "?").$key."=".$val;

			return $retString;
		}
		return false;  // this rule does not apply
	}

	public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
	{
		if (preg_match('%^[a-z0-9()\-_\.]+$%', $pathInfo, $matches))
		{

			if (!empty($matches)) {

				$objCategory = Category::LoadByRequestUrl($matches[0]);
				if ($objCategory instanceof Category) {
					$_GET['cat'] = $matches[0];
					return 'search/browse';
				}

				$objCustomPage = CustomPage::LoadByRequestUrl($matches[0]);
				if ($objCustomPage instanceof CustomPage)
				{
					$_GET['id'] = $objCustomPage->request_url;

					//Reserved keyword for contact us form
					if ($matches[0]=="contact-us")
						return "custompage/contact";
					else
					return "custompage/index";
				}
				else {
					return false;
				}
			}

		}
		return false;  // this rule does not apply
	}
}