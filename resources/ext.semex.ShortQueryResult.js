/**
 * JavasSript for representing some short query result
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 * 
 * @since 0.1
 * @ingroup Semantic Expresiveness
 * 
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */

/**
 * Constructor for short query result representation within JavaScript.
 * @param Node element representing the short Query.
 * @throws Error in case the Short Query Result structure is invalid.
 */
window.semanticExpresiveness.ShortQueryResult = function( element ) {
	this._elem = $( element );
	
	// validation for the short query result:
	if(
		   this.getSource() === false
		|| this.getProperty() === false
		|| this.isAbstractResult() === false
		&& ( this.getRawResult() === null || this.getResult() === null )
	) {
		throw new Error( 'Invalid Short Query Result structure detected' );
	}
};
window.semanticExpresiveness.ShortQueryResult.prototype = {
	/**
	 * @var jQuery
	 */
	_elem: null,
	_querySource: null,
	_queryProperty: null,
	_queryRawResult: null,
	
	/**
	 * Returns the node in the DOM representing this short query result.
	 * @return Node
	 */
	getDOMNode: function() {
		return this._elem;
	},
	
	/**
	 * Returns whether this short query result is an abstract result for some reason, most likely
	 * the query failed in this case.
	 * @return bool
	 */
	isAbstractResult: function() {
		return this._elem.hasClass( 'abstractShortQuery' );
	},
	
	/**
	 * Returns the local page name of the page the displayed query information was taken from.
	 * In case this is an abstract short query result, it is possible that the source was another
	 * unsuccessful short query. In this case, null will be returned.
	 * @return string|null
	 */
	getSource: function() {
		if( this._querySource !== null ) {
			return this._querySource;
		}
		this._querySource = this._getShortQueryInfo( 'source' );
		
		if( this._querySource === false
			&& this.isAbstractResult()
			&& this._elem.children( '.source' ).length == 1
		) {
			// abstract result and another short query as source!
			this._querySource = null;
		}
		return this._querySource;
	},
	
	/**
	 * Returns the property name of the queried value.
	 * @return string
	 */
	getProperty: function() {
		if( this._queryProperty !== null ) {
			return this._queryProperty;
		}
		this._queryProperty = this._getShortQueryInfo( 'type' );
		return this._queryProperty;
	},
	
	/**
	 * Returns the unformatted, raw result of the query. Returns null in case the query went wrong.
	 * @return string[]|null
	 */
	getRawResult: function() {
		if( this._queryRawResult !== null ) {
			return this._queryRawResult;
		}
		
		// query result can have several values which are stored within the title of child elements
		// of the short queries direct child with class 'value'		
		values = this._elem.children( '.value' ).children( '*[title]' );
		if( values.length < 1 ) {
			return null;
		}
		
		// put all values into an array:
		var titles = new Array( values.length );
		
		values.each( function( index, elem ) {
			titles[index] = $( elem ).attr( 'title' )
		} );
		
		this._queryRawResult = titles;
		return titles;
	},
	
	/**
	 * Returns the DOM elements which are part of the formatted result. Returns null in case the query
	 * went wrong. For unformatted result see getRawResult() function.
	 * @return jQuery|null
	 */
	getResult: function() {
		var result = this._elem.children( '.result' );
		if( result.length != 1 ) {
			return null
		}
		return result.contents();
	},
	
	/**
	 * Generic helper to get attached information from the short queries DOM.
	 * @param info string class name of the element where the information lays within the 'title'
	 */
	_getShortQueryInfo: function( info ) {
		var title = this._elem.children( '.' + info + '[title]' );
		if( title.length != 1 ) {
			return false;
		}
		title = $.trim( title.attr( 'title' ) );
		if( title === '' ) {
			return false;
		}
		return title;
	}
};
