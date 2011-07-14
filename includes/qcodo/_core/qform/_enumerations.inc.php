<?php
	// This file describes the various enumeration classes that are used throughout the Qforms layer

	abstract class QBorderStyle {
		const NotSet = 'NotSet';
		const None = 'none';
		const Dotted = 'dotted';
		const Dashed = 'dashed';
		const Solid = 'solid';
		const Double = 'double';
		const Groove = 'groove';
		const Ridge = 'ridge';
		const Inset = 'inset';
		const Outset = 'outset';
	}

	abstract class QDisplayStyle {
		const None = 'none';
		const Block = 'block';
		const Inline = 'inline';
		const NotSet = 'NotSet';
	}

	abstract class QTextAlign {
		const Left = 'left';
		const Right = 'right';
	}

	abstract class QRepeatDirection {
		const Horizontal = 'Horizontal';
		const Vertical = 'Vertical';
	}

	abstract class QGridLines {
		const None = 'none';
		const Horizontal = 'horizontal';
		const Vertical = 'vertical';
		const Both = 'both';
	}

	abstract class QSelectionMode {
		const Single = 'Single';
		const Multiple = 'Multiple';
		const None = 'None';
	}

	abstract class QTextMode {
		const SingleLine = 'SingleLine';
		const MultiLine = 'MultiLine';
		const Password = 'Password';
	}

	abstract class QHorizontalAlign {
		const NotSet = 'NotSet';
		const Left = 'left';
		const Center = 'center';
		const Right = 'right';
		const Justify = 'justify';
	}

	abstract class QVerticalAlign {
		const NotSet = 'NotSet';
		const Top = 'top';
		const Middle = 'middle';
		const Bottom = 'bottom';
	}

	abstract class QBorderCollapse {
		const NotSet = 'NotSet';
		const Separate = 'Separate';
		const Collapse = 'Collapse';
	}
	
	abstract class QDateTimePickerType {
		const Date = 'Date';
		const DateTime = 'DateTime';
		const DateTimeSeconds = 'DateTimeSeconds';
		const Time = 'Time';
		const TimeSeconds = 'TimeSeconds';
	}

	abstract class QCalendarType {
		const DateOnly = 'DateOnly';
		const DateTime = 'DateTime';
		const DateTimeSeconds = 'DateTimeSeconds';
		const TimeOnly = 'TimeOnly';
		const TimeSecondsOnly = 'TimeSecondsOnly';
	}

	abstract class QDateTimePickerFormat {
		const MonthDayYear = 'MonthDayYear';
		const DayMonthYear = 'DayMonthYear';
		const YearMonthDay = 'YearMonthDay';
	}

	abstract class QCrossScripting {
		const Allow = 'Allow';
		const HtmlEntities = 'HtmlEntities';
		const Deny = 'Deny';
	}
	
	abstract class QCallType {
		const Server = 'Server';
		const Ajax = 'Ajax';
		const None = 'None';
	}
	
	abstract class QPosition {
		const Relative = 'relative';
		const Absolute = 'absolute';
		const Fixed = 'fixed';
		const NotSet = 'NotSet';
	}
	
	abstract class QResizeHandleDirection {
		const Vertical = 'Vertical';
		const Horizontal = 'Horizontal';
	}

	abstract class QCursor {
		const NotSet = 'NotSet';
		const Auto = 'auto';
		const CrossHair = 'crosshair';
		const CursorDefault = 'default';
		const Pointer = 'pointer';
		const Move = 'move';
		const EResize = 'e-resize';
		const NEResize = 'ne-resize';
		const NWResize = 'nw-resize';
		const NResize = 'n-resize';
		const SEResize = 'se-resize';
		const SWResize = 'sw-resize';
		const SResize = 's-resize';
		const WResize = 'w-resize';
		const Text = 'text';
		const Wait = 'wait';
		const Help = 'help';
		const Progress = 'progress';
	}

	abstract class QOverflow {
		const NotSet = 'NotSet';
		const Auto = 'auto';
		const Hidden = 'hidden';
		const Scroll = 'scroll';
		const Visible = 'visible';
	}

	abstract class QCausesValidation {
		const None = false;
		const AllControls = true;
		const SiblingsAndChildren = 2;
		const SiblingsOnly = 3;
	}

	abstract class QImageType {
		const Jpeg = 'jpg';
		const Png = 'png';
		const Gif = 'gif';
		const AnimatedGif = 'AnimatedGif';
	}

	abstract class QFileAssetType {
		const Image = 1;
		const Pdf = 2;
		const Document = 3;
	}

	abstract class QMetaControlCreateType {
		const CreateOrEdit = 1;
		const CreateOnRecordNotFound = 2;
		const EditOnly = 3;
	}

	abstract class QMetaControlArgumentType {
		const PathInfo = 1;
		const QueryString = 2;
		const PostData = 3;
	}
?>