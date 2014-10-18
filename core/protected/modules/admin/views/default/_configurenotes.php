<?php
	switch(WsTheme::getThemeNameOrParentName())
	{
		case "brooklyn2014": $this->renderPartial('admin.views.theme._brooklyn2014notes');
							 break;
		default: echo "";
	}