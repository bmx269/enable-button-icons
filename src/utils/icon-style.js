import React from 'react';
import ReactDOMServer from 'react-dom/server';

import { flattenIconsArray } from './icon-functions';
import getIcons from '../icons';

/**
 * Utility to generate the proper CSS selector for layout styles.
 *
 * @param {string} selectors CSS selector, also supports multiple comma-separated selectors.
 * @param {string} append    The string to append.
 *
 * @return {string} - CSS selector.
 */
export function appendSelectors( selectors, append = '' ) {
	return selectors
		.split( ',' )
		.map(
			( subselector ) =>
				`${ subselector }${ append ? ` ${ append }` : '' }`
		)
		.join( ',' );
}

function svgToDataUri( svg ) {
	const encodedSvg = encodeURIComponent( svg )
		.replace( /'/g, '%27' )
		.replace( /"/g, '%22' )
		.replace( /</g, '%3C' )
		.replace( />/g, '%3E' )
		.replace( /#/g, '%23' )
		.replace( /\s+/g, ' ' ); // Minify by removing line breaks and excessive spaces

	return `data:image/svg+xml,${ encodedSvg }`;
}

/**
 * Generate icon styles for the block editor.
 *
 * @since 0.1.0
 * @param {Object}  params                 Function parameters.
 * @param {string}  params.selector        CSS selector for the icon.
 * @param {string}  params.icon            Icon SVG string.
 * @param {string}  params.iconName        Icon name from library.
 * @param {string}  params.customIconColor Custom icon color.
 * @param {Object}  params.style           Block style object.
 * @param {string}  params.iconSize        Icon size value.
 * @param {string}  params.iconSpacing     Icon spacing value.
 * @return {string} CSS string for the icon.
 */
export function getIconStyle( {
	selector,
	icon,
	iconName,
	customIconColor,
	style,
	iconSize,
	iconSpacing,
} ) {
	let output = '';
	const rules = [];
	let svg = icon;

	// If we don't have the icon SVG string but we have an iconName, look it up.
	if ( ! svg && iconName ) {
		const iconsAll = flattenIconsArray( getIcons() );
		const namedIcon = iconsAll.filter( ( i ) => i.name === iconName );
		if ( namedIcon.length > 0 ) {
			if ( React.isValidElement( namedIcon[ 0 ].icon ) ) {
				svg = ReactDOMServer.renderToString( namedIcon[ 0 ].icon );
			} else {
				svg = namedIcon[ 0 ].icon;
			}
		}
	}

	if ( ! svg ) {
		return output;
	}

	const dataUri = svgToDataUri( svg );
	rules.push( `mask-image: url( ${ dataUri } ) !important;` );
	rules.push( `-webkit-mask-image: url( ${ dataUri } ) !important;` );

	// Apply icon size if provided.
	if ( iconSize ) {
		rules.push( `width: ${ iconSize } !important;` );
		rules.push( `height: ${ iconSize } !important;` );
	}

	if ( customIconColor ) {
		rules.push( `color: ${ customIconColor };` );
	}

	if ( rules.length ) {
		output = `${ appendSelectors( selector ) } {
			${ rules.join( ' ' ) };
		}`;
	}

	// Add icon spacing (gap) if provided.
	if ( iconSpacing ) {
		const linkSelector = selector.split( '::' )[ 0 ];
		output += `\n${ linkSelector } {
			gap: ${ iconSpacing } !important;
		}`;
	}

	return output;
}
