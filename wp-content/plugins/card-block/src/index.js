/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( metadata.name, {
	icon: {
		src: <svg version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 615.1 756.6' enable-background='new 0 0 615.1 756.6'>
			<g>
				<polyline points='555.1,555.1 378.3,731.9 24.7,378.3 378.3,24.7 555.1,201.5 ' fill="#fff" stroke="#000" stroke-width="35" stroke-miterlimit="10"/>
				<path class='st0' d='M378.3,166.1l212.1,212.1L378.3,590.4L166.1,378.2 M484.3,484.3L378.2,378.2' fill="#fff" stroke="#000" stroke-width="35" stroke-miterlimit="10"/>
			</g>
		</svg>
	},
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * @see ./save.js
	 */
	save,
} );
