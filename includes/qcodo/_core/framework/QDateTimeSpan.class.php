<?php
	/* Qcodo Development Framework for PHP
	 * http://www.qcodo.com/
	 *
	 * Copyright (C) 2006
	 * Martin Kronstad - Siteman AS - http://www.siteman.no/
	 *
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with this program; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
	 */

	class QDateTimeSpan extends QBaseClass{
		protected $intSeconds;

		/* From: http://tycho.usno.navy.mil/leapsec.html:
			This definition was ratified by the Eleventh General Conference on Weights and Measures in 1960.
			Reference to the year 1900  does not mean that this is the epoch of a mean solar day of 86,400 seconds.
			Rather, it is the epoch of the tropical year of 31,556,925.9747 seconds of ephemeris time.
			Ephemeris Time (ET) was defined as the measure of time that brings the observed positions of the celestial
			bodies into accord with the Newtonian dynamical theory of motion.
		*/
 		const SecondsPerYear	= 31556926;
 		
		// Assume 30 Days per Month
 		const SecondsPerMonth 	= 2592000;
		const SecondsPerDay 	= 86400;
		const SecondsPerHour 	= 3600;
		const SecondsPerMinute 	= 60;

		public function __construct($intSeconds = 0) {
			$this->intSeconds = $intSeconds;
		}

		/*
			Is functions
		*/ 
		
		/**
		 * Checks if the current DateSpan is positive
		 *
		 * @return boolean
		 */
		public function IsPositive(){
			return ($this->intSeconds > 0);
		}

		/**
		 * Checks if the current DateSpan is negative
		 *
		 * @return boolean
		 */
		public function IsNegative(){
			return ($this->intSeconds < 0);
		}

		/**
		 * Checks if the current DateSpan is zero
		 *
		 * @return boolean
		 */
		public function IsZero(){
			return ($this->intSeconds == 0);
		}
		
		/**
		 * Calculates the difference between this DateSpan and another DateSpan
		 *
		 * @param QDateTimeSpan $dtsSpan
		 * @return new QDateTimeSpan
		 */
		public function Difference(QDateTimeSpan $dtsSpan){
			$intDifference = $this->Seconds - $dtsSpan->Seconds;
			$dtsDateSpan = new QDateTimeSpan();
			$dtsDateSpan->AddSeconds($intDifference);
			return $dtsDateSpan;
		}
		
		/*
			SetFrom methods
		*/
		
		/**
		 * Sets current QDateTimeSpan to the difference between two QDateTime objects
		 *
		 * @param QDateTime $dttFrom
		 * @param QDateTime $dttTo
		 */
		public function SetFromQDateTime(QDateTime $dttFrom, QDateTime $dttTo){
			$this->Add($dttFrom->Difference($dttTo));
		}
		
		/*
			Add methods
		*/	
		
		/**
		 * Adds an amount of seconds to the current QDateTimeSpan
		 *
		 * @param int $intSeconds
		 */
		public function AddSeconds($intSeconds){
			$this->intSeconds = $this->intSeconds + $intSeconds;
		}
		
		/**
		 * Adds an amount of minutes to the current QDateTimeSpan
		 *
		 * @param int $intMinutes
		 */
		public function AddMinutes($intMinutes){
			$this->intSeconds = $this->intSeconds + ($intMinutes * QDateTimeSpan::SecondsPerMinute);
		}
		
		/**
		 * Adds an amount of hours to the current QDateTimeSpan
		 *
		 * @param int $intHours
		 */
		public function AddHours($intHours){
			$this->intSeconds = $this->intSeconds + ($intHours * QDateTimeSpan::SecondsPerHour);
		}
		
		/**
		 * Adds an amount of days to the current QDateTimeSpan
		 *
		 * @param int $intDays
		 */
		public function AddDays($intDays){
			$this->intSeconds = $this->intSeconds + ($intDays * QDateTimeSpan::SecondsPerDay);
		}
		
		/**
		 * Adds an amount of months to the current QDateTimeSpan
		 *
		 * @param int $intMonths
		 */
		public function AddMonths($intMonths){
			$this->intSeconds = $this->intSeconds + ($intMonths * QDateTimeSpan::SecondsPerMonth);
		}
		
		/* 
			Get methods
		*/
		
		/**
		 * Calculates the total whole years in the current QDateTimeSpan
		 *
		 * @return int
		 */
		protected function GetYears() {
			$intSecondsPerYear = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerYear : ((-1) * QDateTimeSpan::SecondsPerYear);
			$intYears = floor($this->intSeconds / $intSecondsPerYear);
			if ($this->IsNegative()) $intYears = (-1) * $intYears;
			return $intYears;
		}

		/**
		 * Calculates the total whole months in the current QDateTimeSpan
		 *
		 * @return int
		 */
		protected function GetMonths(){
			$intSecondsPerMonth = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerMonth : ((-1) * QDateTimeSpan::SecondsPerMonth);
			$intMonths = floor($this->intSeconds / $intSecondsPerMonth);
			if($this->IsNegative()) $intMonths = (-1) * $intMonths;
			return $intMonths;
		}
		
		/**
		 * Calculates the total whole days in the current QDateTimeSpan
		 *
		 * @return int
		 */
		protected function GetDays(){
			$intSecondsPerDay = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerDay : ((-1) * QDateTimeSpan::SecondsPerDay);
			$intDays = floor($this->intSeconds / $intSecondsPerDay);
			if($this->IsNegative()) $intDays = (-1) * $intDays;
			return $intDays;
		}

		/**
		 * Calculates the total whole hours in the current QDateTimeSpan
		 *
		 * @return int
		 */
		protected function GetHours(){
			$intSecondsPerHour = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerHour : ((-1) * QDateTimeSpan::SecondsPerHour);
			$intHours = floor($this->intSeconds / $intSecondsPerHour);
			if($this->IsNegative()) $intHours = (-1) * $intHours;
			return $intHours;
		}
		
		/**
		 * Calculates the total whole minutes in the current QDateTimeSpan
		 *
		 * @return int
		 */
		protected function GetMinutes(){
			$intSecondsPerMinute = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerMinute : ((-1) * QDateTimeSpan::SecondsPerMinute);
			$intMinutes = floor($this->intSeconds / $intSecondsPerMinute);
			if($this->IsNegative()) $intMinutes = (-1) * $intMinutes;
			return $intMinutes;
		} 
 		
		/*
			DateMathSettings
		*/
		
		/**
		 * Adds a QDateTimeSpan to current QDateTimeSpan
		 *
		 * @param QDateTimeSpan $dtsSpan
		 */
		public function Add(QDateTimeSpan $dtsSpan){
			$this->intSeconds = $this->intSeconds + $dtsSpan->Seconds;
		}
		
		/**
		 * Subtracts a QDateTimeSpan to current QDateTimeSpan
		 *
		 * @param QDateTimeSpan $dtsSpan
		 */
		public function Subtract(QDateTimeSpan $dtsSpan){
			$this->intSeconds = $this->intSeconds - $dtsSpan->Seconds;
		}

		public function SimpleDisplay(){
			$arrTimearray = $this->GetTimearray();
			$strToReturn = null;

			if($arrTimearray['Years'] != 0) {
				$strFormat = ($arrTimearray['Years'] != 1) ? QApplication::Translate('about %s years') :  QApplication::Translate('a year');
				$strToReturn = sprintf($strFormat, $arrTimearray['Years']);
			}
			elseif($arrTimearray['Months'] != 0){
				$strFormat = ($arrTimearray['Months'] != 1) ? QApplication::Translate('about %s months') : QApplication::Translate('a month');
				$strToReturn = sprintf($strFormat,$arrTimearray['Months']);
			}
			elseif($arrTimearray['Days'] != 0){
				$strFormat = ($arrTimearray['Days'] != 1) ? QApplication::Translate('about %s days') : QApplication::Translate('a day');
				$strToReturn = sprintf($strFormat,$arrTimearray['Days']);
			}
			elseif($arrTimearray['Hours'] != 0){
				$strFormat = ($arrTimearray['Hours'] != 1) ? QApplication::Translate('about %s hours') : QApplication::Translate('an hour');
				$strToReturn = sprintf($strFormat,$arrTimearray['Hours']);
			}
			elseif($arrTimearray['Minutes'] != 0){
				$strFormat = ($arrTimearray['Minutes'] != 1) ? QApplication::Translate('%s minutes') : QApplication::Translate('a minute');
				$strToReturn = sprintf($strFormat,$arrTimearray['Minutes']);
			}
			elseif($arrTimearray['Seconds'] != 0 ){
				$strFormat = ($arrTimearray['Seconds'] != 1) ? QApplication::Translate('%s seconds') : QApplication::Translate('a second');
				$strToReturn = sprintf($strFormat,$arrTimearray['Seconds']);
			}
			
			return $strToReturn;
		}
		
		
		/**
		 * Return an array of timeunints
		 * 
		 *
		 * @return array of timeunits
		 */
		protected function GetTimearray(){
			$intSecondsPerYear = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerYear : ((-1) * QDateTimeSpan::SecondsPerYear);
			$intSecondsPerMonth = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerMonth : ((-1) * QDateTimeSpan::SecondsPerMonth);
			$intSecondsPerDay = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerDay : ((-1) * QDateTimeSpan::SecondsPerDay);
			$intSecondsPerHour = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerHour : ((-1) * QDateTimeSpan::SecondsPerHour);
			$intSecondsPerMinute = ($this->IsPositive()) ? QDateTimeSpan::SecondsPerMinute : ((-1) * QDateTimeSpan::SecondsPerMinute);
			
			$intSeconds = abs($this->intSeconds);

			$intYears = floor($intSeconds / QDateTimeSpan::SecondsPerYear);
			$intSeconds = $intSeconds - ($intYears * QDateTimeSpan::SecondsPerYear);

			$intMonths = floor($intSeconds / QDateTimeSpan::SecondsPerMonth);
			$intSeconds = $intSeconds - ($intMonths * QDateTimeSpan::SecondsPerMonth);

			$intDays = floor($intSeconds / QDateTimeSpan::SecondsPerDay);
			$intSeconds = $intSeconds - ($intDays * QDateTimeSpan::SecondsPerDay);
			
			$intHours = floor($intSeconds / QDateTimeSpan::SecondsPerHour);
			$intSeconds = $intSeconds - ($intHours * QDateTimeSpan::SecondsPerHour);
			
			$intMinutes = floor($intSeconds / QDateTimeSpan::SecondsPerMinute);
			$intSeconds = $intSeconds - ($intMinutes * QDateTimeSpan::SecondsPerMinute);

			$intSeconds = $intSeconds;

			if($this->IsNegative()){
 				// Turn values to negative
				$intYears = ((-1) * $intYears);
				$intMonths = ((-1) * $intMonths);
				$intDays = ((-1) * $intDays);
				$intHours = ((-1) * $intHours);
				$intMinutes = ((-1) * $intMinutes);
				$intSeconds = ((-1) * $intSeconds);
			}

			return array('Years' => $intYears, 'Months' => $intMonths, 'Days' => $intDays, 'Hours' => $intHours, 'Minutes' => $intMinutes,'Seconds' => $intSeconds);
		}

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed the returned property
		 */
		
		public function __get($strName) {
			switch ($strName) {
				case 'Years': return $this->GetYears();
				case 'Months': return $this->GetMonths();
				case 'Days': return $this->GetDays();
				case 'Hours': return $this->GetHours();
				case 'Minutes': return $this->GetMinutes();
				case 'Seconds': return $this->intSeconds;
				case 'Timearray' : return ($this->GetTimearray());

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed the property that was set
		 */
	
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Seconds':
						return ($this->intSeconds = QType::Cast($mixValue, QType::Integer));
					default:
						return (parent::__set($strName, $mixValue));
				}				
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		
		
	}

?>