/**
 * JavasScript to measure some multi-line inline element.
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 * 
 * @since 0.1
 * @ingroup Semantic Expressiveness
 * 
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */
( function( semEx, $, undefined ) {
"use strict";

/**
 * The InlineMeasurer helps to get some information about multi-line inline elements,
 * like where does the element start in the first line, where does it end in the last
 * line and how many lines does it have.
 */
semEx.ui.InlineMeasurer = {};

/**
 * Function to measure some inline element. The function has to temporarily modify the
 * element and add some <span/> at its beginning and end.
 *
 * @returns semEx.ui.InlineMeasurer.Measurement
 * 
 * @ToDo: Add support and test this for right-to-left languages
 * 
 * Note: This would be possible with getClientRects() as well. But it has poor browser
 *       support (IE6+7 zoom behavior).
 */
semEx.ui.InlineMeasurer.measure = function( element ) {
	var elem = $( element );
	var result = new semEx.ui.InlineMeasurer.Measurement( element );

	// add helpers into dom:
	var helper1 = $( '<span/>' );
	var helper2 = $( '<span/>' );
	elem.prepend( helper1 );
	elem.append( helper2 );

	// measure:
	result.isOneLiner = helper1.position().top === helper2.position().top;
	result.firstLineWidth = elem.outerWidth() - ( helper1.offset().left - elem.offset().left );
	result.lastLineOffset = helper2.offset().left - elem.offset().left;

	// destroy helper objects:
	helper1.empty().remove();
	helper2.empty().remove();

	return result;
};

/**
 * Constructor for Object returned by measure() function.
 * @constructor
 * @since 0.1
 *
 * @param {jQuery} element
 *
 * //TODO doesn't really make sense to have the element here
 */
semEx.ui.InlineMeasurer.Measurement = function( element ) {
	this.element = element;
};
semEx.ui.InlineMeasurer.Measurement.prototype = {
	/**
	 * The element the other information relate to. Be aware that the related information
	 * could be 'wrong' due to some DOM updates or other influences already.
	 * @type jQuery
	 */
	element: null,

	/*
	 * Whether the inline-element spreads over several lines. This can change after text or DOM
	 * have been modified or even when re-sizing the browsers viewport.
	 * @type Boolean
	 */	 
	isOneLiner: true,

	/**
	 * Width of the first line. In case the element spreads over several lines, the first and the
	 * last line could be shorter as the whole elements width.
	 * @type Number
	 */
	firstLineWidth: 0,

	/**
	 * Width of the last line. In case the element spreads over several lines, the first and the
	 * last line could be shorter as the whole elements width.
	 * @type Number
	 */
	lastLineWidth: 0
};

}( semanticExpressiveness, jQuery ) );
