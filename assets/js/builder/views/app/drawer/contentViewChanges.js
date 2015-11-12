/**
 * Changes collection view.
 *
 * @package Ninja Forms builder
 * @subpackage App
 * @copyright (c) 2015 WP Ninjas
 * @since 3.0
 */
define( ['builder/views/app/drawer/contentViewChangesItem'], function( viewChangesItem ) {
	var view = Marionette.CollectionView.extend( {
		tagName: 'ul',
		childView: viewChangesItem
	} );

	return view;
} );