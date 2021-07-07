<?php
/**
 * Plugin Name: WP GraphQL for WP Shopify
 * Plugin URI: 
 * Description: A WP GraphQL extension that adds support for WP Shopify
 * Version: 0.0.2
 * Author: Caleb Barnes
 * Author URI: https://github.com/CalebBarnes/wp-graphql-wp-shopify
 */



// register wpsProducts in WpGraphQL
add_filter( 'register_post_type_args', function( $args, $post_type ) {

	if ( 'wps_products' === $post_type ) { 
		$args['show_in_graphql'] = true;
		$args['graphql_single_name'] = 'product';
		$args['graphql_plural_name'] = 'products';
	}

	if ('wps_collections' === $post_type ) { 
		$args['show_in_graphql'] = true;
		$args['graphql_single_name'] = 'collection';
		$args['graphql_plural_name'] = 'collections';
	}

	return $args;

}, 10, 2 );


// resolve field shopifyProductId on Product type
add_action( 'graphql_register_types', function() {
  register_graphql_field( 'Product', 'shopifyProductId', [
    'type' => 'ID',
    'resolve' => function($root) {

		$idPrefix = "gid://shopify/Product/";
		$shopifyProductId = get_post_meta( $root->ID, 'product_id' );

		if ($shopifyProductId && is_array($shopifyProductId)) {

			$uniqueId = base64_encode($idPrefix . $shopifyProductId[0]);

			return $uniqueId;
		} else {
			return null;
		}
    },
  ]);
} );

// resolve field shopifyCollectionId on Collection type
add_action( 'graphql_register_types', function() {
  register_graphql_field( 'Collection', 'shopifyCollectionId', [
    'type' => 'String',
    'resolve' => function($root) {

		$idPrefix = "gid://shopify/Collection/";

		$shopifyCollectionId = get_post_meta( $root->ID, "collection_id");

		if ($shopifyCollectionId && is_array($shopifyCollectionId)) {

			$uniqueId = base64_encode($idPrefix . $shopifyCollectionId[0]);

			return $uniqueId;
		} else {
			return null;
		}
    },
  ]);
} );

