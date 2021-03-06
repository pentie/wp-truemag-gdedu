<?php
class CT_ContentHelper{
	/* 
	 * Get related posts of a post based on conditions
	 *
	 * $posttypes: post types
	 * $tags: tag slug
	 * $postformat : post format
	 */
	function tm_get_related_posts($posttypes, $tags, $postformat, $count, $orderby, $args = array()){
		$args = '';
		if($posttypes==''){$posttypes='post';}
		global $post;
		if($postformat=='video'){
		$args = array(
			'post_type' => $posttypes,
			'posts_per_page' => $count,
			'post_status' => 'publish',
			'post__not_in' =>  array(get_the_ID($post)),
			'ignore_sticky_posts' => 1,
			'orderby' => $orderby,
			'tag' => $tags,
			'tax_query' => array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-video',
			))
		);
		}
		else if($postformat=='standard')
		{
			$args = array(
			'post_type' => $posttypes,
			'posts_per_page' => $count,
			'post_status' => 'publish',
			'post__not_in' =>  array(get_the_ID($post)),
			'ignore_sticky_posts' => 1,
			'orderby' => $orderby,
			'tag' => $tags,
			'tax_query' => array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-video',
				'operator' => 'NOT IN'
			)),
		);
		}else {
			$args = array(
			'post_type' => $posttypes,
			'posts_per_page' => $count,
			'post_status' => 'publish',
			'post__not_in' =>  array(get_the_ID($post)),
			'orderby' => $orderby,
			'tag' => $tags,
			'ignore_sticky_posts' => 1
		);	
		}
		
		$query = new WP_Query($args);
		
		return $query;
	}
	/* 
	 * Get item for trending, popular
	 * $conditions : most by :view, comment, likes, latest
	 * $number : Number of post
	 * $ids : List id
	 *
	 *
	 */
	function tm_get_popular_posts($conditions, $tags, $number, $ids,$sort_by, $categories, $args = array(),$themes_pur){
		$args = '';

		if($conditions=='most_viewed' && $ids==''){
			  $args = array(
				  'post_type' => 'post',
				  'posts_per_page' => $number,
				  'meta_key' => '_count-views_all',
				  'orderby' => 'meta_value_num',
				  'order' => $sort_by,
				  'post_status' => 'publish',
				  'tag' => $tags,
				  'ignore_sticky_posts' => 1,
					'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-audio',
						'operator' => 'NOT IN'
					)),
				  
			  );	
				if($themes_pur!='0'){
				$args['tax_query'] =  array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-video',
					));
				}			
		}elseif($conditions=='most_comments'  && $ids==''){
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $number,
				'orderby' => 'comment_count',
				'order' => $sort_by,
				'post_status' => 'publish',
				'tag' => $tags,
				'ignore_sticky_posts' => 1,
					'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-audio',
						'operator' => 'NOT IN'
					)),
				);
				if($themes_pur!='0'){
				$args['tax_query'] =  array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-video',
					));
				}			

		}elseif($conditions=='high_rated'  && $ids==''){
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $number,
				'meta_key' => '_count-views_all',
				'orderby' => 'meta_value_num',
				'order' => $sort_by,
				'post_status' => 'publish',
				'tag' => $tags,
				'ignore_sticky_posts' => 1,
					'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-audio',
						'operator' => 'NOT IN'
					)),
				);
				if($themes_pur!='0'){
				$args['tax_query'] =  array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-video',
					));
				}			

		}elseif($conditions=='playlist'){
			$ids = explode(",", $ids);
			$gc = array();
			$dem=0;
			foreach ( $ids as $grid_cat ) {
				$dem++;
				array_push($gc, $grid_cat);
			}
			$args = array(
				'post_type' => 'post',
				'post__in' =>  $gc,
				'posts_per_page' => $number,
				'orderby' => 'post__in',
				'order' => $sort_by,
				'ignore_sticky_posts' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-video',
				)),
			);
		}elseif($ids!=''){
			$ids = explode(",", $ids);
			$gc = array();
			$dem=0;
			foreach ( $ids as $grid_cat ) {
				$dem++;
				array_push($gc, $grid_cat);
			}
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $number,
				'order' => $sort_by,
				'post_status' => 'publish',
				'tag' => $tags,
				'post__in' =>  $gc,
				'ignore_sticky_posts' => 1,
				'tax_query' => array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => 'post-format-audio',
					'operator' => 'NOT IN'
				)),
				
			);
		}elseif($ids=='' && $conditions=='latest'){
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $number,
				'order' => $sort_by,
				'post_status' => 'publish',
				'tag' => $tags,
				'ignore_sticky_posts' => 1,
					'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-audio',
						'operator' => 'NOT IN'
					)),
				);
				if($themes_pur!='0'){
				$args['tax_query'] =  array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-video',
					));
				}			

		}elseif($ids=='' && $conditions=='random'){
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $number,
				'order' => $sort_by,
				'orderby' => 'rand',
				'post_status' => 'publish',
				'tag' => $tags,
				'ignore_sticky_posts' => 1,
					'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-audio',
						'operator' => 'NOT IN'
					)),

			);
		}
		if(!is_array($categories)) {
			if(isset($categories)){
				$cats = explode(",",$categories);
				if(is_numeric($cats[0])){
					//$args += array('category__in' => $cats);
					$args['category__in'] = $cats;
				}else{			 
					$args['category_name'] = $categories;
				}
			}
		}else if(count($categories) > 0){
			$args += array('category__in' => $categories);
		}
		$query = new WP_Query($args);
		
		return $query;
	}


}

?>