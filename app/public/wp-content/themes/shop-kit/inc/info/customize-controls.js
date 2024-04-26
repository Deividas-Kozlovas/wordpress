( function( api ) {

	// Extends our custom "shop-kit-pro" section.
	api.sectionConstructor['shop-kit-pro'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
