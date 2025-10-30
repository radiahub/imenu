// ============================================================================
// Module      : imenucli.js
// Version     : 1.0
//
// Author      : Denis Patrice <denispatrice@yahoo.com>
// Copyright   : Copyright (c) Denis Patrice Dipl.-Ing. 2010-2025
//               All rights reserved
//
// Application : imenu
// Description : CLI API
//
// Date+Time of change   By     Description
// --------------------- ------ ----------------------------------------------
// 20-Jan-25 00:00 WIT   Denis  Deployment V. 2025 "Raymond Chandler"
//
// ============================================================================

var imenucli = {

	editor: {

		ace : null,

		show : function() {
			imenucli.terminal.hide();
			jQuery("#DIV_EDITOR").show();
			imenucli.editor.ace.focus();
		}, 

		hide : function() {
			jQuery("#DIV_EDITOR").hide();
		},

		init : function() {
			console.info("IN imenucli.editor.init()");

			imenucli.editor.ace = ace.edit("DIV_EDITOR_ACE");
			imenucli.editor.ace.setTheme("ace/theme/monokai");

			var options = {
				fontFamily          : "Monaco, Courier, monospace !important",
				fontSize            : 16,
				highlightGutterLine : false,
				highlightActiveLine : true,
				showGutter          : true,
				showLineNumbers     : true,
				showPrintMargin     : false,
				tabSize             : 2,
				wrap                : true
			};

			imenucli.editor.ace.setOptions(options);
			var jsonMode = ace.require("ace/mode/json").Mode;
			imenucli.editor.ace.session.setMode(new jsonMode());

		}

	},

	terminal: {

		show : function() {
			imenucli.editor.hide();
			jQuery("#DIV_TERMINAL").show();
		}, 

		hide : function() {
			jQuery("#DIV_TERMINAL").hide();
		},

		init : function() {
			console.info("IN imenucli.terminal.init()");
		}
	},

	run : function() 
	{
		console.log("IN imenucli.run()");
		imenucli.terminal.init();
		imenucli.editor.init();
		imenucli.editor.show();

		jQuery("#BTN_GO_SAVE").on("click", function(){
			ripple("BTN_GO_SAVE", function(){
				console.log("CLICKED");
			});
		});

		jQuery("#BTN_GO_RESET").on("click", function(){
			ripple("BTN_GO_RESET", function(){
				console.log("CLICKED");
			});
		});

	}

};


// End of file: imenucli.js
// ============================================================================