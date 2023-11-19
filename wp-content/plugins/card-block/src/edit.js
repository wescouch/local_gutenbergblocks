/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck  } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({attributes,setAttributes}) {
	return (
		<div { ...useBlockProps() }>
			<div className="card-image-container">
				{attributes.optional_image && (
					<img src={attributes.optional_image} alt="" className="card-image" />
				)}
				<MediaUploadCheck className="testing">
					<MediaUpload
						onSelect={(media) => setAttributes({optional_image: media.url})}
						allowedTypes={ ['image'] }
						value={ attributes.optional_image }
						render={ ( { open } ) => (
							<Button variant="secondary" className="mediaupload-card-image" onClick={ open }>Select/Upload Card Image</Button>
						) }
					/>
				</MediaUploadCheck>
			</div>
			<div className="card-content-container">
				<RichText
					tagName="h4"
					placeholder={__('Card heading', 'card-block')}
					value={attributes.heading}
					onChange={(val) => setAttributes({heading: val})}
				/>
				<RichText
					tagName="p"
					placeholder={__('Lorem ipsum dolor sit amet', 'card-block')}
					value={attributes.content}
					onChange={(val) => setAttributes({content: val})}
				/>
			</div>
		</div>
	);
}
