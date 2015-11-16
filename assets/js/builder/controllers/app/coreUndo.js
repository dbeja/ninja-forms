/**
 * All of the core undo functions. Listens on the 'changes' channel for an undo request.
 * 
 * @package Ninja Forms builder
 * @subpackage Main App
 * @copyright (c) 2015 WP Ninjas
 * @since 3.0
 */
define( [], function() {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			nfRadio.channel( 'changes' ).reply( 'undo:changeSetting', this.undoChangeSetting, this );

			nfRadio.channel( 'changes' ).reply( 'undo:sortFields', this.undoSortFields, this );
			nfRadio.channel( 'changes' ).reply( 'undo:addField', this.undoAddField, this );
			nfRadio.channel( 'changes' ).reply( 'undo:removeField', this.undoRemoveField, this );
			nfRadio.channel( 'changes' ).reply( 'undo:duplicateField', this.undoDuplicateField, this );

			nfRadio.channel( 'changes' ).reply( 'undo:addListOption', this.undoAddListOption, this );
			nfRadio.channel( 'changes' ).reply( 'undo:removeListOption', this.undoRemoveListOption, this );
			nfRadio.channel( 'changes' ).reply( 'undo:sortListOptions', this.undoSortListOptions, this );
		},

		/**
		 * Undo settings that have been changed.
		 * 
		 * @since  3.0
		 * @param  backbone.model 	change 	model of our change
		 * @param  boolean 			remove 	should we remove this item from our change collection
		 * @return void
		 */
		undoChangeSetting: function( change, remove ) {
			var fieldModel = change.get( 'model' );
			var changes = change.get( 'changes' );
			var attr = changes.attr;
			var before = changes.before;
			fieldModel.set( attr, before );
			this.maybeRemoveChange( change, remove );
		},

		/**
		 * Undo field sorting.
		 * 
		 * @since  3.0
		 * @param  backbone.model 	change 	model of our change
		 * @param  boolean 			remove 	should we remove this item from our change collection
		 * @return void
		 */
		undoSortFields: function( change, remove ) {
			var objModels = change.get( 'data' );

			_.each( objModels, function( changeModel ) {
				var before = changeModel.before;
				var fieldModel = changeModel.model;
				fieldModel.set( 'order', before );
			} );

			var fieldCollection = nfRadio.channel( 'fields' ).request( 'get:fieldCollection' );
			fieldCollection.sort();
			this.maybeRemoveChange( change, remove );
		},

		/**
		 * Undo adding a field.
		 * Loops through our change collection and removes any change models based upon the one we're removing.
		 * 
		 * @since  3.0
		 * @param  backbone.model 	change 	model of our change
		 * @param  boolean 			remove 	should we remove this item from our change collection
		 * @return void
		 */
		undoAddField: function( change, remove ) {
			var fieldModel = change.get( 'model' );
			
			var changeCollection = nfRadio.channel( 'changes' ).request( 'get:changeCollection' );
			var results = changeCollection.where( { model: fieldModel } );

			var fieldCollection = nfRadio.channel( 'fields' ).request( 'get:fieldCollection' );
			delete fieldCollection.newIDs[ fieldModel.get( 'id' ) ];
			
			_.each( results, function( model ) {
				if ( model !== change ) {
					changeCollection.remove( model );
				}
			} );
			
			var fieldCollection = nfRadio.channel( 'fields' ).request( 'get:fieldCollection' );
			fieldCollection.remove( fieldModel );
			this.maybeRemoveChange( change, remove );
		},		

		/**
		 * Undo adding a field.
		 * Loops through our change collection and removes any change models based upon the one we're removing.
		 * 
		 * @since  3.0
		 * @param  backbone.model 	change 	model of our change
		 * @param  boolean 			remove 	should we remove this item from our change collection
		 * @return void
		 */
		undoDuplicateField: function( change, remove ) {
			var fieldModel = change.get( 'model' );
			
			var changeCollection = nfRadio.channel( 'changes' ).request( 'get:changeCollection' );
			var results = changeCollection.where( { model: fieldModel } );

			_.each( results, function( model ) {
				if ( model !== change ) {
					changeCollection.remove( model );
				}
			} );
			
			var fieldCollection = nfRadio.channel( 'fields' ).request( 'get:fieldCollection' );
			fieldCollection.remove( fieldModel );
			this.maybeRemoveChange( change, remove );
		},

		/**
		 * Undo removing a field
		 * 
		 * @since  3.0
		 * @param  backbone.model 	change 	model of our change
		 * @param  boolean 			remove 	should we remove this item from our change collection
		 * @return void
		 */
		undoRemoveField: function( change, remove ) {
			var fieldModel = change.get( 'model' );
			nfRadio.channel( 'fields' ).request( 'add:field', fieldModel );

			var fieldCollection = nfRadio.channel( 'fields' ).request( 'get:fieldCollection' );
			delete fieldCollection.removedIDs[ fieldModel.get( 'id' ) ];

			var changeCollection = nfRadio.channel( 'changes' ).request( 'get:changeCollection' );
			var results = changeCollection.where( { model: fieldModel } );

			_.each( results, function( model ) {
				if ( model !== change ) {
					model.set( 'disabled', false );
				}
			} );

			this.maybeRemoveChange( change, remove );
		},

		undoAddListOption: function( change, remove ) {
			var model = change.get( 'model' );

			var changeCollection = nfRadio.channel( 'changes' ).request( 'get:changeCollection' );
			var results = changeCollection.where( { model: model } );

			_.each( results, function( changeModel ) {
				if ( changeModel !== change ) {
					changeCollection.remove( changeModel );
				}
			} );

			model.collection.remove( model );
			this.maybeRemoveChange( change, remove );
		},

		undoRemoveListOption: function( change, remove ) {
			var model = change.get( 'model' );
			var collection = change.get( 'data' ).collection;
			collection.add( model );

			var changeCollection = nfRadio.channel( 'changes' ).request( 'get:changeCollection' );
			var results = changeCollection.where( { model: model } );

			_.each( results, function( model ) {
				if ( model !== change ) {
					model.set( 'disabled', false );
				}
			} );

			this.maybeRemoveChange( change, remove );
		},

		undoSortListOptions: function( change, remove ) {
			var data = change.get( 'data' );
			var collection = data.collection;
			var objModels = data.objModels;

			_.each( objModels, function( changeModel ) {
				var before = changeModel.before;
				var optionModel = changeModel.model;
				optionModel.set( 'order', before );
			} );

			collection.sort();
			this.maybeRemoveChange( change, remove );
		},

		/**
		 * If our undo action was requested to 'remove' the change from the collection, remove it.
		 * 
		 * @since  3.0
		 * @param  backbone.model 	change 	model of our change
		 * @param  boolean 			remove 	should we remove this item from our change collection
		 * @return void
		 */
		maybeRemoveChange: function( change, remove ) {
			// Update preview.
			nfRadio.channel( 'app' ).request( 'update:db' );			
			var remove = typeof remove !== 'undefined' ? remove : true;
			if ( remove ) {
				var changeCollection = nfRadio.channel( 'changes' ).request( 'get:changeCollection' );
				changeCollection.remove( change );
				if ( 0 == changeCollection.length ) {
					nfRadio.channel( 'app' ).request( 'update:setting', 'clean', true );
					nfRadio.channel( 'app' ).request( 'close:drawer' );
				}
			}
		}

	});

	return controller;
} );