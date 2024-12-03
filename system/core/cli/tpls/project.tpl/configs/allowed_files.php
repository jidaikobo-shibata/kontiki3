<?php
define(
	'KONTIKI3_ALLOWED_FILES',
	[
		// Images
		'image/jpeg',
		'image/png',

		// PDF
		'application/pdf',

		// Microsoft Office files
		'application/msword',                // .doc
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
		'application/vnd.ms-excel',          // .xls
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       // .xlsx
		'application/vnd.ms-powerpoint',     // .ppt
		'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx

		// LibreOffice files
		'application/vnd.oasis.opendocument.text',        // .odt
		'application/vnd.oasis.opendocument.spreadsheet', // .ods
		'application/vnd.oasis.opendocument.presentation', // .odp
		'application/vnd.oasis.opendocument.graphics',    // .odg
		'application/vnd.oasis.opendocument.text-master', // .odm
		'application/vnd.oasis.opendocument.text-template', // .ott
		'application/vnd.oasis.opendocument.spreadsheet-template', // .ots
		'application/vnd.oasis.opendocument.presentation-template', // .otp

		// Braille files (BES)
		'application/x-bes',

		// Text files
		'text/plain', // .txt

		// Audio files
		'audio/mpeg',  // .mp3

		// Compressed files
		'application/zip', // .zip
	]
);
