/**
 * JavasSript to measure some multi-line inline element.
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 * 
 * @since 0.1
 * @ingroup Semantic Expresiveness
 * 
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */
"use strict";

/**
 * The InlineMeasurer helps to get some information about multi-line inline elements,
 * like where does the element start in the first line, where does it end in the last
 * line and how many lines does it have.
 */
window.semanticExpresiveness.ui.InlineMeasurer = {};

/**
 * Function to meassure some inline element. The function has to temporarily modify the
 * element and add some <span/> at its beginning and end.
 * @returns semanticExpresiveness.ui.InlineMeasurer.Measurement
 * 
 * @ToDo: Add support and test this for right-to-left languages
 * 
 * Note: This would be possible with getClientRects() as well. But it has poor browser
 *       support (IE6+7 zoom behavior).
 */
window.semanticExpresiveness.ui.InlineMeasurer.measure = function( element ) {
	var elem = $( element );
	var result = new window.semanticExpresiveness.ui.InlineMeasurer.Measurement( element );

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
	helper1.empty().remove()
	helper2.empty().remove()

	return result;
}

/**
 * Constructor for Object returned by measure() function.
 */
window.semanticExpresiveness.ui.InlineMeasurer.Measurement = function( element ) {
	this.element = element;
};
window.semanticExpresiveness.ui.InlineMeasurer.Measurement.prototype = {
	/**
	 * The element the other informations relate to. Be aware that the related informations
	 * could be 'wrong' due to some DOM updates or other influences already.
	 */
	element: null,

	/*
	 * Whether the inline-element spreads over several lines. This can change after text or DOM
	 * have been modified or even when re-sizing the browsers viewport.
	 * @var boolean
	 */	 
	isOneLiner: true,

	/**
	 * Width of the first line. In case the element spreads over several lines, the first and the
	 * last line could be shorter as the whole elements width.
	 * @var integer
	 */
	firstLineWidth: 0,

	/**
	 * Width of the last line. In case the element spreads over several lines, the first and the
	 * last line could be shorter as the whole elements width.
	 * @var integer
	 */
	lastLineWidth: 0
};
