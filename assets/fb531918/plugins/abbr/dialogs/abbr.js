/**
 * Copyright (c) 2014, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * The abbr plugin dialog window definition.
 *
 * Created out of the CKEditor Plugin SDK:
 * http://docs.ckeditor.com/#!/guide/plugin_sdk_sample_1
 */

// Our dialog definition.
CKEDITOR.dialog.add( 'abbrDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: 'Abbreviation Properties',
		minWidth: 400,
		minHeight: 200,

		// Dialog window content definition.
		contents: [
			{
				// Definition of the Basic Settings dialog tab (page).
				id: 'tab-basic',
//				label: 'Basic Settings',

				// The tab content.
				elements: [
					{
						// html field for the abbreviation text.
						type: 'html',
						id: 'abbr',
                        html: '<span id="htmlEl">Abbreviation</span>'
					},
					{
						// Text input field for the abbreviation title (explanation).
						type: 'text',
						id: 'title',
						label: 'Explanation',

						// Called by the main setupContent method call on dialog initialization.
						setup: function( element ) {
							this.setValue( element.getAttribute( "title" ) );
						},

						// Called by the main commitContent method call on dialog confirmation.
						commit: function( element ) {
							element.setAttribute( "title", this.getValue() );
						}
					},
                    {
                        type:'html',
                        id: 'hint',
                        html:'<p>leave explanation empty to delete abbreviation</p>'
                    }
				]
			}
		],

		// Invoked when the dialog is loaded.
		onShow: function() {

			// Get the selection from the editor.
			var selection = editor.getSelection();

			// Get the element at the start of the selection.
			var element = selection.getStartElement();

			// Get the <abbr> element closest to the selection, if it exists.
			if ( element )
				element = element.getAscendant( 'abbr', true );

			// Create a new <abbr> element if it does not exist.
			if ( !element || element.getName() != 'abbr' ) {
				element = editor.document.createElement( 'abbr' );
                var text = selection.getSelectedText();
                element.setText(text);
                element.setAttribute("title",text);

				// Flag the insertion mode for later use.
				this.insertMode = true;
			}
			else
				this.insertMode = false;

			// Store the reference to the <abbr> element in an internal property, for later use.
			this.element = element;

            // Set the html for abbreviation
            var dialogDoc = this.getElement().getDocument();
            var htmlElement = dialogDoc.getById('htmlEl');
            htmlElement.setHtml('<span id="htmlEl">Abbreviation: <strong>'+element.getText()+'</strong></span>');


			// Invoke the setup methods of all dialog window elements, so they can load the element attributes.
				this.setupContent( this.element );
		},

		// This method is invoked once a user clicks the OK button, confirming the dialog.
		onOk: function() {

			// The context of this function is the dialog object itself.
			// http://docs.ckeditor.com/#!/api/CKEDITOR.dialog
			var dialog = this;

			// Create a new <abbr> element.
			var abbr = this.element;

			// Invoke the commit methods of all dialog window elements, so the <abbr> element gets modified.
			this.commitContent( abbr );

            if (!abbr.getAttribute("title") && !this.insertMode) {
                var html = abbr.getHtml();
                abbr.remove();
                editor.insertHtml(html);
            }

			// Finally, if in insert mode, insert the element into the editor at the caret position.
			if ( abbr.getAttribute("title") && this.insertMode ) {
                var range = editor.getSelection().getRanges()[ 0 ];
                range.deleteContents();
                range.select(); // Select emptied range to place the caret in its place.
                editor.insertElement( abbr );
            }

		}
	};
});
