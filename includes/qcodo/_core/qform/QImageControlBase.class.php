<?php
	// This class will render an Image Control of any image file in the system

	abstract class QImageControlBase extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strBackColor = 'ffffff';
		protected $blnScaleCanvasDown = false;
		protected $blnScaleImageUp = true;

		// BEHAVIOR
		protected $strImageType = null;
		protected $intQuality = 100;

		protected $strImagePath;
		protected $strAlternateText;

		// CacheFolder Location for Cached Generated Images (if applicable)
		protected $strCacheFolder = null;
		protected $strCacheFilename = null;

		// Internally Used
		protected $strSourceImageType = null;
		protected $strCachedActualFilePath = null;

		//////////
		// Methods
		//////////
		public function ParsePostData() {}
		public function Validate() {return true;}
		
		public function __construct($objParentObject, $strControlId = null) {
			if ($objParentObject)
				parent::__construct($objParentObject, $strControlId);
		}

		public function RenderAsImgSrc($blnDisplayOutput = true) {
			// If not a visible control, then don't process anything
			if (!$this->blnVisible) return;

			// Ensure that the ImagePath is Valid
			if (!$this->strImagePath || !file_exists($this->strImagePath))
				throw new QCallerException('ImagePath is not defined or does not exist');

			// Serialize and Hash Data
			$strSerialized = $this->Serialize();
			$strHash = md5($strSerialized);

			// Figure Out Image Filename
			if ($this->strCacheFilename)
				$strImageFilename = $this->strCacheFilename;
			else if ($this->strImageType)
				$strImageFilename = $strHash . '.' . $this->strImageType;
			else
				$strImageFilename = $strHash . '.' . $this->strSourceImageType;

			// Figure out IMG SRC path based on Caching prefs
			if ($this->strCacheFolder) {
				$strFilePath = sprintf('%s%s/%s',
					__DOCROOT__,
					str_replace(__VIRTUAL_DIRECTORY__, '', $this->strCacheFolder),
					$strImageFilename);
				if (!file_exists($strFilePath))
					$this->RenderImage($strFilePath);

				$strPath = sprintf('%s/%s',
					$this->strCacheFolder,
					$strImageFilename);

				// Store Cache Filepath Info
				$this->strCachedActualFilePath = $strFilePath;
			} else {
				$strPath = sprintf('%s/_core/image.php/%s?q=%s',
					__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__,
					$strImageFilename,
					$strSerialized
				);
			}

			// Output or Display
			if ($blnDisplayOutput)
				print($strPath);
			else
				return $strPath;
		}

		protected function GetControlHtml() {
			try {
				// Figure Out the Path
				$strPath = $this->RenderAsImgSrc(false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			if ($this->strCachedActualFilePath) {
				$objDimensions = getimagesize($this->strCachedActualFilePath);

				// Setup Style and Other Attribute Information (EXCEPT for "BackColor")
				// Use actual "Width" and "Height" values from cached image
				$strBackColor = $this->strBackColor;
				$strWidth = $this->strWidth;
				$strHeight = $this->strHeight;
				$this->strBackColor = null;
				$this->strWidth = $objDimensions[0];
				$this->strHeight = $objDimensions[1];
				$strStyle = $this->GetStyleAttributes();
				if ($strStyle)
					$strStyle = sprintf(' style="%s"', $strStyle);
				$this->strBackColor = $strBackColor;
				$this->strWidth = $strWidth;
				$this->strHeight = $strHeight;
			} else {
				// Setup Style and Other Attribute Information (EXCEPT for "BackColor", "Width" and "Height")
				$strBackColor = $this->strBackColor;
				$strWidth = $this->strWidth;
				$strHeight = $this->strHeight;
				$this->strBackColor = null;
				$this->strWidth = null;
				$this->strHeight = null;
				$strStyle = $this->GetStyleAttributes();
				if ($strStyle)
					$strStyle = sprintf(' style="%s"', $strStyle);
				$this->strBackColor = $strBackColor;
				$this->strWidth= $strWidth;
				$this->strHeight = $strHeight;
			}

			$strAlt = null;
			if ($this->strAlternateText)
				$strAlt = ' alt="' . QApplication::HtmlEntities($this->strAlternateText) . '"';

			// Render final "IMG SRC" tag
			$strToReturn = sprintf('<img id="%s" src="%s" %s%s%s/>',
				$this->strControlId,
				$strPath,
				$this->GetAttributes(),
				$strStyle,
				$strAlt);
			return $strToReturn;
		}

		public function Serialize() {
			$objControl = clone($this);
			$objControl->objForm = null;
			$objControl->objParentControl = null;
			$objControl->strCachedActualFilePath = null;
			$strData = serialize($objControl);
			if (function_exists('gzcompress'))
				$strData = base64_encode(gzcompress($strData, 9));
			else
				$strData = base64_encode($strData);

			$strData = str_replace('+', '-', $strData);
			$strData = str_replace('/', '_', $strData);
			
			return $strData;
		}
		
		public static function Run() {
			$strData = QApplication::QueryString('q');
			$strData = str_replace('-', '+', $strData);
			$strData = str_replace('_', '/', $strData);

			$strData = base64_decode($strData);

			if (function_exists('gzcompress'))
				$strData = gzuncompress($strData);

			$objLabel = unserialize($strData);
			$objLabel->RenderImage();
		}
		
		protected function FlowThrough($strPath) {
			// No Image Type changing
			if ((!$this->strImageType) || ($this->strImageType == $this->strSourceImageType)) {
				if ($strPath)
					copy($this->strImagePath, $strPath);
				else {
					$this->SetupContentType();
					print file_get_contents($this->strImagePath, true);
				}

			// Image Type WILL CHANGE
			} else {
				switch ($this->strSourceImageType) {
					case QImageType::Jpeg:
						$objImage = imagecreatefromjpeg($this->strImagePath);
						break;
					case QImageType::Png:
						$objImage = imagecreatefrompng($this->strImagePath);
						break;
					case QImageType::Gif:
						$objImage = imagecreatefromgif($this->strImagePath);
						break;
					default:
						throw new QCallerException('Original image is of an invalid file path');
				}

				// If No Path is Specified, then we are OUTPUTTING to the BROWSER DIRECTLY
				if (!$strPath)
					$this->SetupContentType();

				switch ($this->strImageType) {
					case QImageType::Jpeg:
						if ($strPath)
							imagejpeg($objImage, $strPath, $this->intQuality);
						else
							imagejpeg($objImage, null, $this->intQuality);
						break;
					case QImageType::Png:
						if ($strPath)
							imagepng($objImage, $strPath);
						else 
							imagepng($objImage);
						break;
					case QImageType::Gif:
						if ($strPath)
							imagegif($objImage, $strPath);
						else
							imagegif($objImage);
						break;
					default:
						throw new QCallerException('ImageType is not a known image type');
				}
				
				imagedestroy($objImage);
			}
		}

		protected function SetupContentType() {
			// TODO: Update Cache Parameters
			QApplication::$CacheControl = 'cache';
			header('Expires: Wed, 20 Mar 2019 05:00:00 GMT');
			header('Pragma: cache');

			if (!($strImageType = $this->strImageType))
				$strImageType = $this->strSourceImageType;

			switch ($strImageType) {
				case QImageType::Jpeg:
				case QImageType::Png:
				case QImageType::Gif:
					header('Content-Type: image/' . $strImageType);
					break;
				default:
					throw new Exception('Invalid Image Type');
			}
		}

		public function RenderImage($strPath = null) {
			if (!$this->strImagePath)
				throw new QCallerException('No Image Path was set');

			// Flow Through if No Size Information
			if ((!$this->strWidth) && (!$this->strHeight)) {
				$this->FlowThrough($strPath);
				return;
			}

			// Get Image Size
			$objDimensions = getimagesize($this->strImagePath);
			$intSourceWidth = $objDimensions[0];
			$intSourceHeight = $objDimensions[1];

			// We need to calculate the following values:
			$intDestinationWidth = null;
			$intDestinationHeight = null;

			$intCanvasWidth = null;
			$intCanvasHeight = null;

			/////////////////////////////////////////////
			// Calculate Dimensions: Based ONLY on WIDTH
			/////////////////////////////////////////////
			if ($this->strWidth && !$this->strHeight) {
				// DIMENSIONS THE SAME -- Flow Through
				if ($this->strWidth == $intSourceWidth) {
					$this->FlowThrough($strPath);
					return;
				}

				// DESTINATION LARGER than source
				if ($this->strWidth > $intSourceWidth) {
					// Do NOT Scale "Up"
					if (!$this->blnScaleImageUp) {
						// Scale Canvas -- Flow Through
						if ($this->blnScaleCanvasDown) {
							$this->FlowThrough($strPath);
							return;
						}

						// Do NOT Scale Canvas -- Canvas width matches requested width. Destionation matches Source dimensions.
						$intDestinationWidth = $intSourceWidth;
						$intDestinationHeight = $intSourceHeight;
						$intCanvasWidth = $this->strWidth;
						$intCanvasHeight = $intSourceHeight;

					// SCALE UP source image -- Canvas and Destination widths both match requested width.  Canvas and Destination height both need to scale up.
					} else {
						$intDestinationWidth = $this->strWidth;
						$intDestinationHeight = $intSourceHeight * $this->strWidth / $intSourceWidth;
						$intCanvasWidth = $this->strWidth;
						$intCanvasHeight = $intSourceHeight * $this->strWidth / $intSourceWidth;
					}

				// DESTINATION SMALLER than source -- Scale Down Source Image.  Canvas is size of image
				} else {
					$intDestinationWidth = $this->strWidth;
					$intDestinationHeight = $intSourceHeight * $this->strWidth / $intSourceWidth;
					$intCanvasWidth = $intDestinationWidth;
					$intCanvasHeight = $intDestinationHeight;
				}

			/////////////////////////////////////////////
			// Calculate Dimensions: Based ONLY on HEIGHT
			/////////////////////////////////////////////
			} else if ($this->strHeight && !$this->strWidth) {
				// DIMENSIONS THE SAME -- Flow Through
				if ($this->strHeight == $intSourceHeight) {
					$this->FlowThrough($strPath);
					return;
				}

				// DESTINATION LARGER than source
				if ($this->strHeight > $intSourceHeight) {
					// Do NOT Scale "Up"
					if (!$this->blnScaleImageUp) {
						// Scale Canvas -- Flow Through
						if ($this->blnScaleCanvasDown) {
							$this->FlowThrough($strPath);
							return;
						}

						// Do NOT Scale Canvas -- Canvas height matches requested height. Destionation matches Source dimensions.
						$intDestinationWidth = $intSourceWidth;
						$intDestinationHeight = $intSourceHeight;
						$intCanvasWidth = $intSourceWidth;
						$intCanvasHeight = $this->strHeight;

					// SCALE UP source image -- Canvas and Destination heights both match requested height.  Canvas and Destination widths both need to scale up.
					} else {
						$intDestinationWidth = $intSourceWidth * $this->strHeight / $intSourceHeight;
						$intDestinationHeight = $this->strHeight;
						$intCanvasWidth = $intSourceWidth * $this->strHeight / $intSourceHeight;
						$intCanvasHeight = $this->strHeight;
					}

				// DESTINATION SMALLER than source -- Scale Down Source Image.  Canvas is size of image
				} else {
					$intDestinationWidth = $intSourceWidth * $this->strHeight / $intSourceHeight;
					$intDestinationHeight = $this->strHeight;
					$intCanvasWidth = $intDestinationWidth;
					$intCanvasHeight = $intDestinationHeight;
				}
				
			/////////////////////////////////////////////
			// Calculate Dimensions based on BOTH DIMENSIONS
			/////////////////////////////////////////////
			} else {
				// DIMENSIONS THE SAME -- Flow Through
				if (($this->strHeight == $intSourceHeight) && ($this->strWidth == $intSourceWidth)) {
						$this->FlowThrough($strPath);
						return;
				}

				// DESTINATION LARGER than source
				if (($this->strHeight >= $intSourceHeight) && ($this->strWidth >= $intSourceWidth)) {
					// Do NOT Scale "Up"
					if (!$this->blnScaleImageUp) {
						// Scale Canvas - Flow Through
						if ($this->blnScaleCanvasDown) {
							$this->FlowThrough($strPath);
							return;
						}
						
						// Do NOT Scale Canvas -- Canvas Dimensions match Requested Dimensions.  Destination dimensions match Source Dimensions
						$intDestinationWidth = $intSourceWidth;
						$intDestinationHeight = $intSourceHeight;
						$intCanvasWidth = $this->strWidth;
						$intCanvasHeight = $this->strHeight;
					}
				}
				
				// If no Dest Width is defined, then we haven't done any calculations yet.  This means that we are either
				// scaling up OR down the source image.  Scale Destination Dimensions to the maximum possible, given the requested width/height
				if (!$intDestinationWidth) {
					// Calculate Image Proportions for Source and Destination
					$fltSourceProportions = $intSourceWidth / $intSourceHeight;
					$fltDestProportions = $this->strWidth / $this->strHeight;

					// Destination is WIDER than Source -- therefore HEIGHT defined by Requested Height, and Width is calculated
					if ($fltDestProportions > $fltSourceProportions) {
						$intDestinationWidth = $intSourceWidth * $this->strHeight / $intSourceHeight;
						$intDestinationHeight = $this->strHeight;

					// Destination is TALLER than Source -- therefore WIDTH defined by Requested Width, and Height is calculated
					} else if ($fltDestProportions < $fltSourceProportions) {
						$intDestinationWidth = $this->strWidth;
						$intDestinationHeight = $intSourceHeight * $this->strWidth / $intSourceWidth;

					// Destination Proportions MATCH Source Proportions -- Width/Height defined by Requested Width/Height
					} else {
						$intDestinationWidth = $this->strWidth;
						$intDestinationHeight = $this->strHeight;
						$intCanvasWidth = $intDestinationWidth;
						$intCanvasHeight = $intDestinationHeight;
					}
				}
				
				// If No Canvas Dimensions Defined, Calculate this now
				if (!$intCanvasWidth) {
					if ($this->blnScaleCanvasDown) {
						$intCanvasWidth = $intDestinationWidth;
						$intCanvasHeight = $intDestinationHeight;
					} else {
						$intCanvasWidth = $this->strWidth;
						$intCanvasHeight = $this->strHeight;
					}
				}
			}

			// Create Destination Image
			$objFinalImage = imagecreatetruecolor($intCanvasWidth, $intCanvasHeight);

			// Setup Background
			$intRed = hexdec(substr($this->strBackColor, 0, 2));
			$intGreen = hexdec(substr($this->strBackColor, 2, 2));
			$intBlue = hexdec(substr($this->strBackColor, 4));
			$clrBackground = imagecolorallocate($objFinalImage, $intRed, $intGreen, $intBlue);

			// Paint Background
			imagefilledrectangle($objFinalImage, 0, 0, $intCanvasWidth, $intCanvasHeight, $clrBackground);

			// Load Source Image Into Memory
			switch ($this->strSourceImageType) {
				case QImageType::Jpeg:
					$objImage = imagecreatefromjpeg($this->strImagePath);
					break;
				case QImageType::Png:
					$objImage = imagecreatefrompng($this->strImagePath);
					break;
				case QImageType::Gif:
					$objImage = imagecreatefromgif($this->strImagePath);
					break;
				default:
					throw new QCallerException('Invalid Source Image Type');
			}

			// Calculate X and Y position
			$intX = round(($intCanvasWidth - $intDestinationWidth) / 2.0);
			$intY = round(($intCanvasHeight - $intDestinationHeight) / 2.0);

			// Resample Image Over
			imagecopyresampled($objFinalImage, $objImage, $intX, $intY, 0, 0, $intDestinationWidth, $intDestinationHeight, $intSourceWidth, $intSourceHeight);

			// Output the Image (if path isn't specified, output to buffer.  Otherwise, output to disk)
			if (!$strPath)
				$this->SetupContentType();

			if (!($strImageType = $this->strImageType))
				$strImageType = $this->strSourceImageType;

			switch ($strImageType) {
				case QImageType::Gif:
					if ($strPath)
						imagegif($objFinalImage, $strPath);
					else
						imagegif($objFinalImage);
					break;
				case QImageType::Jpeg:
					if ($strPath)
						imagejpeg($objFinalImage, $strPath, $this->intQuality);
					else
						imagejpeg($objFinalImage, null, $this->intQuality);
					break;
				case QImageType::Png:
					if ($strPath)
						imagepng($objFinalImage, $strPath);
					else
						imagepng($objFinalImage);
					break;
				default:
					throw new QCallerException('Invalid Image Type');
			}

			imagedestroy($objImage);
			imagedestroy($objFinalImage);
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "ScaleCanvasDown": return $this->blnScaleCanvasDown;
				case "ScaleImageUp": return $this->blnScaleImageUp;

				// BEHAVIOR
				case "ImageType": return $this->strImageType;
				case "Quality": return $this->intQuality;

				// MISCELLANEOUS
				case "CacheFolder": return $this->strCacheFolder;
				case "CacheFilename": return $this->strCacheFilename;
				case "ImagePath": return $this->strImagePath;
				case "AlternateText": return $this->strAlternateText;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}



		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "ScaleCanvasDown":
					try {
						$this->blnScaleCanvasDown = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ScaleImageUp":
					try {
						$this->blnScaleImageUp = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				// BEHAVIOR
				case "ImageType":
					try {
						$this->strImageType = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Quality":
					try {
						$this->intQuality = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "CacheFolder":
					try {
						$this->strCacheFolder = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "CacheFilename":
					try {
						$this->strCacheFilename = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ImagePath":
					try {
						$this->strImagePath = QType::Cast($mixValue, QType::String);

						if (!$this->strImagePath || !is_file($this->strImagePath))
							throw new QCallerException('ImagePath is not defined or does not exist');

						$this->strImagePath = realpath($this->strImagePath);

						$strSourceImageType = trim(strtolower(substr($this->strImagePath, strrpos($this->strImagePath, '.') + 1)));
						switch ($strSourceImageType) {
							case 'jpeg':
							case 'jpg':
								$this->strSourceImageType = QImageType::Jpeg;
								break;
							case 'png':
								$this->strSourceImageType = QImageType::Png;
								break;
							case 'gif':
								$this->strSourceImageType = QImageType::Gif;
								break;
							default:
								throw new QCallerException('Image Type cannot be determined: ' . $mixValue);
						}

						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "AlternateText":
					try {
						$this->strAlternateText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				// OVERRIDDEN SETTERS
				case "BackColor":
					try {
						$mixValue = strtolower(QType::Cast($mixValue, QType::String));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					if (strlen($mixValue) != 6)
						throw new QInvalidCastException('BackColor must be a 6-digit hexadecimal value');

					// Verify ControlId is only Hexadecimal Digits
					$strMatches = array();
					$strPattern = '/[a-f0-9]*/';
					preg_match($strPattern, $mixValue, $strMatches);
					if (count($strMatches) && ($strMatches[0] == $mixValue))
						return ($this->strBackColor = $mixValue);
					else
						throw new QInvalidCastException('BackColor must be a 6-digit hexadecimal value');

					break;

				case "Width":
					try {
						$this->strWidth = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Height":
					try {
						$this->strHeight = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>