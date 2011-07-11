<?php
	class QRssFeed extends QBaseClass {
		// Required RSS 2 Fields
		protected $strTitle;
		protected $strLink;
		protected $strDescription;

		// Optional RSS 2 Fields
		// TODO: Fields that are commented out are currently not supported because they are either
		// complex RSS tags that need additional coding to implement and/or they are rarely used by readers
		protected $strLanguage;
		protected $strCopyright;
		protected $strManagingEditor;
		protected $strWebMaster;
		protected $dttPubDate;
		protected $dttLastBuildDate;
//		protected $strCategory;
		protected $strGenerator;
		protected $strDocs = 'http://blogs.law.harvard.edu/tech/rss';
//		protected $strCloud;
		protected $strTtl;
		protected $objImage;
//		protected $strRating;
//		protected $strTextInput;
//		protected $strSkipHours;
//		protected $strSkipDays;

		protected $objItemArray = array();

		public function __construct($strTitle, $strLink, $strDescription) {	
			$this->strTitle = $strTitle;
			$this->strLink = $strLink;
			$this->strDescription = $strDescription;
			
			$this->strGenerator = 'Qcodo Development Framework ' . QCODO_VERSION;
		}

		public function GetXml() {
			$strToReturn = "<rss version=\"2.0\">\r\n<channel>\r\n";
			$strToReturn .= sprintf("	<title>%s</title>\r\n", $this->strTitle);
			$strToReturn .= sprintf("	<link>%s</link>\r\n", $this->strLink);
			$strToReturn .= sprintf("	<description>%s</description>\r\n", $this->strDescription);
			
			if ($this->strLanguage)
				$strToReturn .= sprintf("	<language>%s</language>\r\n", $this->strLanguage);
			if ($this->strCopyright)
				$strToReturn .= sprintf("	<copyright>%s</copyright>\r\n", $this->strCopyright);
			if ($this->strManagingEditor)
				$strToReturn .= sprintf("	<managingEditor>%s</managingEditor>\r\n", $this->strManagingEditor);
			if ($this->strWebMaster)
				$strToReturn .= sprintf("	<webMaster>%s</webMaster>\r\n", $this->strWebMaster);
			if ($this->dttPubDate)
				$strToReturn .= sprintf("	<pubDate>%s</pubDate>\r\n", $this->dttPubDate->__toString(QDateTime::FormatRfc822));
			if ($this->dttLastBuildDate)
				$strToReturn .= sprintf("	<lastBuildDate>%s</lastBuildDate>\r\n", $this->dttLastBuildDate->__toString(QDateTime::FormatRfc822));
			if ($this->strGenerator)
				$strToReturn .= sprintf("	<generator>%s</generator>\r\n", $this->strGenerator);
			if ($this->strDocs)
				$strToReturn .= sprintf("	<docs>%s</docs>\r\n", $this->strDocs);
			if ($this->strTtl)
				$strToReturn .= sprintf("	<ttl>%s</ttl>\r\n", $this->strTtl);
			if ($this->objImage)
				$strToReturn .= $this->objImage->GetXml($this->strTitle, $this->strLink);

			foreach ($this->objItemArray as $objItem)
				$strToReturn .= $objItem->GetXml();

			$strToReturn .= "</channel>\r\n</rss>\r\n";
			
			return $strToReturn;
		}

		public function Run() {
			ob_clean();
			header('Content-type: text/xml');
			if (QApplication::$EncodingType)
				printf("<?xml version=\"1.0\" encoding=\"%s\" ?>\r\n", QApplication::$EncodingType);
			else
				_p("<?xml version=\"1.0\" ?>\r\n", false);

			_p($this->GetXml(), false);
		}

		public function AddItem(QRssItem $objItem) {
			array_push($this->objItemArray, $objItem);
		}

		public function __get($strName) {
			try {
				switch ($strName) {
					case 'Title': return $this->strTitle;
					case 'Link': return $this->strLink;
					case 'Description': return $this->strDescription;
					case 'Language': return $this->strLanguage;
					case 'Copyright': return $this->strCopyright;
					case 'ManagingEditor': return $this->strManagingEditor;
					case 'WebMaster': return $this->strWebMaster;
					case 'PubDate': return $this->dttPubDate;
					case 'LastBuildDate': return $this->dttLastBuildDate;
					case 'Generator': return $this->strGenerator;
					case 'Docs': return $this->strDocs;
					case 'Ttl': return $this->strTtl;
					case 'Image': return $this->objImage;
					default: return parent::__get($strName);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Title': return ($this->strTitle = QType::Cast($mixValue, QType::String));
					case 'Link': return ($this->strLink = QType::Cast($mixValue, QType::String));
					case 'Description': return ($this->strDescription = QType::Cast($mixValue, QType::String));
					case 'Language': return ($this->strLanguage = QType::Cast($mixValue, QType::String));
					case 'Copyright': return ($this->strCopyright = QType::Cast($mixValue, QType::String));
					case 'ManagingEditor': return ($this->strManagingEditor = QType::Cast($mixValue, QType::String));
					case 'WebMaster': return ($this->strWebMaster = QType::Cast($mixValue, QType::String));
					case 'PubDate': return ($this->dttPubDate = QType::Cast($mixValue, QType::DateTime));
					case 'LastBuildDate': return ($this->dttLastBuildDate = QType::Cast($mixValue, QType::DateTime));
					case 'Generator': return ($this->strGenerator = QType::Cast($mixValue, QType::String));
					case 'Docs': return ($this->strDocs= QType::Cast($mixValue, QType::String));
					case 'Ttl': return ($this->strTtl = QType::Cast($mixValue, QType::String));
					case 'Image': return ($this->objImage = QType::Cast($mixValue, 'QRssImage'));
					default: return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}

	class QRssImage extends QBaseClass {
		protected $strUrl;
		protected $strTitle;
		protected $strLink;

		public function __construct($strUrl, $strTitle = null, $strLink = null) {
			$this->strUrl = $strUrl;
			$this->strTitle = $strTitle;
			$this->strLink = $strLink;
		}

		public function GetXml($strTitle, $strLink) {
			$strToReturn = "	<image>\r\n";
			$strToReturn .= sprintf("		<url>%s</url>\r\n", $this->strUrl);
			$strToReturn .= sprintf("		<title>%s</title>\r\n", ($this->strTitle) ? $this->strTitle : $strTitle);
			$strToReturn .= sprintf("		<link>%s</link>\r\n", ($this->strLink) ? $this->strLink : $strLink);

			$objImageSize = @getimagesize($this->strUrl);

			if ($objImageSize) {
				$strToReturn .= sprintf("		<width>%s</width>\r\n", $objImageSize[0]);
				$strToReturn .= sprintf("		<height>%s</height>\r\n", $objImageSize[1]);
			}

			$strToReturn .= "	</image>\r\n";

			return $strToReturn;
		}
	}

	class QRssCategory extends QBaseClass {
		protected $strCategory;
		protected $strDomain;

		public function __construct($strCategory, $strDomain = null) {
			$this->strCategory = $strCategory;
			$this->strDomain = $strDomain;
		}

		public function GetXml() {
			if ($this->strDomain)
				return sprintf("		<category domain=\"%s\">%s</category>\r\n", $this->strDomain, $this->strCategory);
			else
				return sprintf("		<category>%s</category>\r\n", $this->strCategory);
		}
	}

	class QRssItem extends QBaseClass {
		// Required RSS 2 Item Fields
		protected $strTitle;
		protected $strLink;
		protected $strDescription;

		// Optional RSS 2 Item Fields
		// TODO: Fields that are commented out are currently not supported because they are either
		// complex RSS tags that need additional coding to implement and/or they are rarely used by readers
		protected $strAuthor;
		protected $objCategoryArray = array();
		protected $strComments;
//		protected $strEnclosure;
		protected $strGuid;
		protected $blnGuidPermaLink;
		protected $dttPubDate;
//		protected $strSource;

		public function __construct($strTitle, $strLink, $strDescription = null) {
			$this->strTitle = $strTitle;
			$this->strLink = $strLink;
			$this->strDescription = $strDescription;
		}

		public function GetXml() {
			$strToReturn = "	<item>\r\n";
			$strToReturn .= sprintf("		<title>%s</title>\r\n", QString::XmlEscape($this->strTitle));
			$strToReturn .= sprintf("		<link>%s</link>\r\n", QString::XmlEscape($this->strLink));
			$strToReturn .= sprintf("		<description>%s</description>\r\n", QString::XmlEscape($this->strDescription));

			if ($this->strAuthor)
				$strToReturn .= sprintf("		<author>%s</author>\r\n", QString::XmlEscape($this->strAuthor));
			foreach ($this->objCategoryArray as $objCategory)
				$strToReturn .= $objCategory->GetXml();
			if ($this->strComments)
				$strToReturn .= sprintf("		<comments>%s</comments>\r\n", QString::XmlEscape($this->strComments));
			if ($this->strGuid)
				$strToReturn .= sprintf("		<guid isPermaLink=\"%s\">%s</guid>\r\n", ($this->blnGuidPermaLink) ? 'true' : 'false', $this->strGuid);
			if ($this->dttPubDate)
				$strToReturn .= sprintf("		<pubDate>%s</pubDate>\r\n", $this->dttPubDate->__toString(QDateTime::FormatRfc822));

			$strToReturn .= "	</item>\r\n";

			return $strToReturn;
		}

		public function AddCategory(QRssCategory $objCategory) {
			array_push($this->objCategoryArray, $objCategory);
		}

		public function __get($strName) {
			try {
				switch ($strName) {
					case 'Title': return $this->strTitle;
					case 'Link': return $this->strLink;
					case 'Description': return $this->strDescription;
					case 'Author': return $this->strAuthor;
					case 'Comments': return $this->strComments;
					case 'Guid': return $this->strGuid;
					case 'GuidPermaLink': return $this->blnGuidPermaLink;
					case 'PubDate': return $this->dttPubDate;
					default: return parent::__get($strName);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Title': return ($this->strTitle = QType::Cast($mixValue, QType::String));
					case 'Link': return ($this->strLink = QType::Cast($mixValue, QType::String));
					case 'Description': return ($this->strDescription = QType::Cast($mixValue, QType::String));
					case 'Author': return ($this->strAuthor = QType::Cast($mixValue, QType::String));
					case 'Comments': return ($this->strComments = QType::Cast($mixValue, QType::String));
					case 'Guid': return ($this->strGuid = QType::Cast($mixValue, QType::String));
					case 'GuidPermaLink': return ($this->blnGuidPermaLink = QType::Cast($mixValue, QType::Boolean));
					case 'PubDate': return ($this->dttPubDate = QType::Cast($mixValue, QType::DateTime));
					default: return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}
?>