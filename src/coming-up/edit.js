/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

import { ServerSideRender } from '@wordpress/editor';
import { Fragment} from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { TextControl, PanelBody, PanelRow, ToggleControl } from '@wordpress/components';


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
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */


export default function edit ( { attributes, className, isSelected, setAttributes } ) {

	const onChangePostType = (event) => {
		setAttributes({postType: event});
	};

	const onChangeShowDate = ( event ) => {
		setAttributes( { showDate: !attributes.showDate});
	}
	const onChangeShowTitle = ( event ) => {
		setAttributes( { showTitle: !attributes.showTitle});
	}
	const onChangeShowTitleAsLink = ( event ) => {
		setAttributes( { showTitleAsLink: !attributes.showTitleAsLink});
	}
	const onChangeShowExcerpt = ( event ) => {
		setAttributes( { showExcerpt: !attributes.showExcerpt });
	}

	const help = __("Choose the post type to display. Default 'post'", 'sb-sb-coming-up');

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody>
					<ToggleControl
						label={ __( 'Show date', 'sb-coming-up' ) }
						checked={ !! attributes.showDate }
						onChange={ onChangeShowDate }
					/>
					<ToggleControl
						label={ __( 'Show title', 'sb-coming-up' ) }
						checked={ !! attributes.showTitle }
						onChange={ onChangeShowTitle }
					/>
					<ToggleControl
						label={ __( 'Show title as link', 'sb-coming-up' ) }
						checked={ !! attributes.showTitleAsLink }
						onChange={ onChangeShowTitleAsLink }
					/>
					<ToggleControl
						label={ __( 'Show Excerpt', 'sb-coming-up' ) }
						checked={ !! attributes.showExcerpt }
						onChange={ onChangeShowExcerpt }
					/>
					<PanelRow>
					<TextControl label={__("Post Type", 'sb-coming-up')} value={attributes.postType}
								 onChange={onChangePostType} help={help}/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender
				block="oik-sb/sb-coming-up" attributes={attributes}
			/>
		</Fragment>
	);
}
