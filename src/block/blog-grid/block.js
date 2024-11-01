/**
 * BLOCK: blog-grid
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

import attributes from "./attributes";


const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

const { Component,Fragment } = wp.element;
const { withSelect } = wp.data;

var el                = wp.element.createElement; 

const { 
	InspectorControls, AlignmentToolbar
} = wp.editor;

const {
	PanelBody, SelectControl, ToggleControl, RangeControl, QueryControls, TextControl
} = wp.components;

var sizes = ultimate_blog_layouts_size.imagesizes ;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/ 
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
 registerBlockType( 'blg/blog-grid', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Blog Grid','ultimate-blog-layouts'), // Block title.
	description: __( 'Display Posts in Grid','ultimate-blog-layouts'), // Block description.
	icon: 'grid-view', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'bloglayouts', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
	__("Blog Grid",'ultimate-blog-layouts'),
	__("Blogs",'ultimate-blog-layouts'),
	__("Posts",'ultimate-blog-layouts'),
	__("Grid",'ultimate-blog-layouts'),
	__("Ultimate Blog Layouts Grid",'ultimate-blog-layouts')
	],
	attributes,

	/**
	* The edit function describes the structure of your block in the context of the editor.
	* This represents what the editor will render when the block is used.
	*
	* The "edit" property must be a valid function.
	*
	* @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	*
	* @param {Object} props Props.
	* @returns {Mixed} JSX Component.
	*/
	edit:withSelect((select, props ) => {

		const { 
			category, order, orderBy, perPage
		} = props.attributes;

		const query = {
			post_status: 'publish',
			orderby: orderBy,
			order: order,
			per_page: perPage,
			categories: category,
		}
		const cat_list = {
			per_page: 100,
		};
		return {
			terms: select('core').getEntityRecords('taxonomy','category',cat_list),
			posts: select('core').getEntityRecords('postType','post',query),
		};

	})
	( function( props ) {

		const { 
			excerptLength, showExcerpt, category, order, orderBy, column, perPage,
			showText, text, showAuth, showDate, showTerms, layouts, imageSize, showImage
		} = props.attributes;

		if (!props.posts || props.terms === null) {
			return (
				el('p',{className:props.className}, __('Loading...','ultimate-blog-layouts'))
				);
		}
		if (props.posts.length === 0 ){
			return (
				el('p',{className:props.className},__('No Posts','ultimate-blog-layouts'))
				);
		}

		let options = [];

		var result =Object.entries(sizes);

		if (result) {
			result.map((size ) =>{
				options.push({label:size[1], value: size[0]});
			});
		}

		return [
		<Fragment>
		<InspectorControls>
		<PanelBody title={__('Settings','ultimate-blog-layouts')}>

		<QueryControls  
		{ ...{ orderBy, order } }
		numberOfItems 			= {perPage}
		categoriesList 			= {props.terms}
		selectedCategoryId 		= {category}
		onCategoryChange 		= {value => props.setAttributes({ category: value })}
		onOrderChange 			= {value => props.setAttributes({ order: value })}
		onOrderByChange 		= {value => props.setAttributes({ orderBy: value })}
		onNumberOfItemsChange 	= {value => props.setAttributes({ perPage: value })}
		/>

		<RangeControl 
		label= {__('Columns','ultimate-blog-layouts')}
		value= {column}
		onChange={value => props.setAttributes({column: value})}
		min= {1}
		max= {4}
		/>
		<SelectControl
		label= {__('Select Image Size','ultimate-blog-layouts')}
		value= {imageSize}
		onChange={value => props.setAttributes({imageSize: value})}
		options= {options}
		/>
		</PanelBody>
		<PanelBody title = {__('Layouts','ultimate-blog-layouts')}  initialOpen = {false}>
		<SelectControl
		label = {__('Select Layout','ultimate-blog-layouts')}
		value = {layouts}
		options = {[ { label: __('Layout 1','ultimate-blog-layouts'), value: 'layout-1' },
		{ label: __('Layout 2','ultimate-blog-layouts'), value: 'layout-2' },
		{ label: __('Layout 3','ultimate-blog-layouts'), value: 'layout-3' },
		]}
		onChange = { value => props.setAttributes({layouts: value}) }
		/>
		</PanelBody>
		<PanelBody title = {__('Content','ultimate-blog-layouts')} initialOpen = {false}>
		<ToggleControl 
		label = {__('Display Image','ultimate-blog-layouts')}
		checked = {showImage}
		onChange = {value => props.setAttributes({ showImage: value })}
		/>
		<hr/>
		<ToggleControl 
		label = {__('Display Author','ultimate-blog-layouts')}
		checked = {showAuth}
		onChange = {value => props.setAttributes({ showAuth: value })}
		/>
		<hr/>
		<ToggleControl 
		label = {__('Display Date','ultimate-blog-layouts')}
		checked = {showDate}
		onChange = {value => props.setAttributes({ showDate: value })}
		/>
		<hr/>
		<ToggleControl
		label = {__('Display Category','ultimate-blog-layouts')}
		checked = {showTerms}
		onChange = {value => props.setAttributes({ showTerms: value })}
		/>
		<hr/>
		<ToggleControl
		label = {__('Display Excerpt','ultimate-blog-layouts')}
		checked = {showExcerpt}
		onChange = {value => props.setAttributes({ showExcerpt: value })}
		/>
		{showExcerpt && (
			<RangeControl
			label = {__('Excerpt Length','ultimate-blog-layouts')}
			value = {excerptLength}
			onChange = {value => props.setAttributes({ excerptLength: value })}
			min = {1}
			max = {55}
			/>
			)}
		<hr/>
		<ToggleControl
		label = {__('Display Read More','ultimate-blog-layouts')}
		checked = {showText}
		onChange = {value => props.setAttributes({ showText: value })}
		/>
		{ showText && ( 
			<TextControl
			label = {__('Read More Label','ultimate-blog-layouts')}
			value = {text}
			onChange = {value => props.setAttributes({ text: value })}
			/>
			)}
		</PanelBody>
		</InspectorControls>

		<section className = { 'ultimate-blog-layouts blog-grid'}>
		<div className = { 'blog-grid-wrapper blg-col-' + column}>
		{ props.posts.map((post) => 

			<article className = { 'blog-item'}>
			{ layouts === 'layout-3' && ( 
				<div className="blg-inner-box">
				<h3><a href={post.link} target="_blank">{post.title.rendered ? post.title.rendered : __('Untitled','ultimate-blog-layouts')}</a></h3>
				{(showAuth || showDate || showTerms) && (<div className="blg-meta-box">
				{showAuth && 
					( <span className="blg-author"><a href={post.author_info.author_link} target="_blank">{post.author_info.display_name}</a></span> )
				}
				{showDate && ( <span className="blg-date"> {moment(post.date).format('MMMM D, Y')} </span> )}
				{showTerms && ( <span className="blg-category" dangerouslySetInnerHTML={{__html: post.category_list}}/>)}
				</div>)} 
				</div> )
			}
			{showImage && post.featured_media ? (
				<div className = "blg-image-wrapper" >
				<img src={post.featured_image_urls[imageSize][0]} alt="image" 
				width={post.featured_image_urls[imageSize][1]}
				height={post.featured_image_urls[imageSize][2]} />
				</div>
				) : ( null )
			}
			<div className="content-box">

			{
				layouts === 'layout-1' && ( <div className="blg-inner-box">
				<h3><a href={post.link} target="_blank">{post.title.rendered ? post.title.rendered : 'Untitled'}</a></h3>
				{(showAuth || showDate || showTerms) && (<div className="blg-meta-box">
				{showAuth && ( <span className="blg-author"><a href={post.author_info.author_link} target="_blank">{post.author_info.display_name}</a></span> )}
				{showDate && ( <span className="blg-date"> {moment(post.date).format('MMMM D, Y')} </span> )}
				{showTerms && ( <span className="blg-category" dangerouslySetInnerHTML={{__html: post.category_list}}/>)}
				</div> )}
				</div> )
			}

			{
				layouts === 'layout-2' && ( <div className="blg-inner-box">
				{(showAuth || showDate || showTerms) && (<div className="blg-meta-box">
				{showAuth && ( <span className="blg-author"><a href={post.author_info.author_link} target="_blank">{post.author_info.display_name}</a></span> )}
				{showDate && ( <span className="blg-date"> {moment(post.date).format('MMMM D, Y')} </span> )}
				{showTerms && ( <span className="blg-category" dangerouslySetInnerHTML={{__html: post.category_list}}/>)}
				</div> )}
				<h3><a href={post.link} target="_blank">{post.title.rendered ? post.title.rendered : 'Untitled'}</a></h3>

				</div>)
			}
			{showExcerpt && 
				( <div className="blg-content" 
				dangerouslySetInnerHTML={{__html: ultimate_blog_layouts_excerpt(post.excerpt.rendered, excerptLength) }}
				/> )
			}
			{showText && 
				( <div className="blg-link"><a href={post.link} target="_blank">{text}</a></div> )
			}

			</div>  
			</article>
			
			)
		}
		</div>
		</section>
		</Fragment>

		]
	}),

	/**
	* The save function defines the way in which the different attributes should be combined
	* into the final markup, which is then serialized by Gutenberg into post_content.
	*
	* The "save" property must be specified and must be a valid function.
	*
	* @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	*
	* @param {Object} props Props.
	* @returns {Mixed} JSX Frontend HTML.
	*/
	save: ( props ) => {
		return null ;
	},

} );

			function ultimate_blog_layouts_excerpt(str, length){

				var test = str.split( ' ' ).splice( 0, length ).join( ' ' );

				if(str.split(' ').length > length){
					test += '...';
				}
				return test;

			}
