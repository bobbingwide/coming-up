<?php

/**
 * @package sb-coming-up
 * @copyright (C) Copyright Bobbing Wide 2021
 * Class SB_Coming_Up_Block
 */

class SB_Coming_Up_Block {

	private $className;
	private $postType;
	private $showDate;
	private $showTitle;
	private $showTitleAsLink;
	private $showExcerpt;
	private $showContent;
	private $showMore;

	function __construct() {
		$this->validate_attributes( [] );
	}

	/**
	 * Validates the attributes, setting defaults.
	 *
	 * @param $attributes
	 */
	function validate_attributes( $attributes ) {
		//bw_trace2();
		$this->className = isset( $attributes['className']) ? $attributes['className'] : 'wp-block-oik-sb-coming-up';
		$postType = isset( $attributes['postType']) ? $attributes['postType'] : 'post';
		$this->postType = explode( ',', $postType );
		$this->showDate = isset( $attributes['showDate']) ? $attributes['showDate'] : true;
		$this->showTitle = isset( $attributes['showTitle']) ? $attributes['showTitle'] : true;
		$this->showTitleAsLink = isset( $attributes['showTitleAsLink']) ? $attributes['showTitleAsLink'] : true;
		$this->showExcerpt = isset( $attributes['showExcerpt']) ? $attributes['showExcerpt'] : false;
		$this->showContent = false;
		$this->showMore = false;
	}

	function render( $attributes ) {
		$attributes = $this->validate_attributes( $attributes );
		$html = '<div class="'. $this->className . '">';
		$posts = $this->fetch_posts();
		$html .= $this->display_posts( $attributes, $posts );
		$html .= '</div>';
		return $html;
	}

	function fetch_posts() {
		$args = [ 'post_type' => $this->postType,
		          'post_status' => 'future',
		          'orderby' => 'date',
		          'order' => 'asc'];
		$posts = get_posts( $args );
		return $posts;
	}

	function display_posts( $attributes, $posts ) {
		$html='';
		$html.='<div';
		foreach ( $posts as $post ) {
			$html .= $this->display_post( $attributes, $post );
		}
		$html.='</div>';
		return $html;
	}

	function display_post( $attributes, $post ) {
		$html ='<div>';
		if ( $this->showDate ) {
			$html .= $this->render_post_date( $post );
		} else {
			$html .= "<div></div>";
		}

		if ( $this->showTitle ) {
			$html.=$this->render_post_title( $post );
		} else {
			$html .= "<div></div>";
		}

		if ( $this->showExcerpt ) {
			$html.=$this->render_post_excerpt( $post );
		} else {
			$html .= "<div></div>";
		}
		$html.='</div>';
		return $html;
	}

	function render_post_date( $post ) {
		$date = $post->post_date;
		$html = '<div>';

		if ( $date ) {
			$format = get_option( 'date_format' );
			$date = strtotime( $date );
			$html .= date_i18n( $format, $date );
		}
		$html .= '</div>';
		return $html;
	}

	function render_post_title( $post ) {
		$title = get_the_title( $post->ID );
		if ( $this->showTitleAsLink ) {
			$attributes = [];
			$attributes['linkTarget'] = '_self';
			$attributes['rel'] = '';
			$title = sprintf( '<a href="%1s" target="%2s" rel="%3s">%4s</a>', get_the_permalink( $post->ID ), $attributes['linkTarget'], $attributes['rel'], $title );
		}
		$html = $title;

		return $html;

	}

	/**
	 * Renders the excerpt.
	 *
	 * To ensure we get the same excerpt in the editor as in the front end.
	 * we need to ensure that the `wp_trim_excerpt()` filter function is
	 * one of the filter functions for `get_the_excerpt`.
	 * This will then return the excerpt determined from the post_content if post_excerpt is blank.
	 * Note: Some themes display both the excerpt and the post content.
	 *
	 * @param $post
	 * @return string
	 */
	function render_post_excerpt( $post ) {
		if ( !has_filter( 'get_the_excerpt', 'wp_trim_excerpt' )) {
			add_filter( 'get_the_excerpt', 'wp_trim_excerpt', 10, 2 );
		}
		$excerpt = get_the_excerpt( $post->ID );
		bw_trace2( $excerpt, 'excerpt');
		//print_r( $post );
		//echo $excerpt;
		$html = '<div>';
		$html .= $excerpt;
		$html .= '</div>';
		return $html;
	}

	/**

	[bw_pages post_type='post' post_status='future' format="d L" fields=title,post_status,post_date orderby=date order=asc]
	 */
}
