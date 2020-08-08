/**
 * @file
 * Displays cytoscape
 */

(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.csdisplayUpload = {
       attach: function(context, settings) {
          $('.cy', context).each(function() {
            var cy = cytoscape({
              container: $('.cy'),
              elements: drupalSettings.cytoscape.elements,
              style: drupalSettings.cytoscape.style,
              layout: drupalSettings.cytoscape.layout,
              // initial viewport state:
              zoom: 1,
              pan: { x: 0, y: 0 },
              // interaction options:
              minZoom: 1e-50,
              maxZoom: 1e50,
              zoomingEnabled: true,
              userZoomingEnabled: true,
              panningEnabled: true,
              userPanningEnabled: true,
              boxSelectionEnabled: true,
              selectionType: 'single',
              touchTapThreshold: 8,
              desktopTapThreshold: 4,
              autolock: false,
              autoungrabify: false,
              autounselectify: false,

              // rendering options:
              headless: false,
              styleEnabled: true,
              hideEdgesOnViewport: false,
              textureOnViewport: false,
              motionBlur: false,
              motionBlurOpacity: 0.2,
              wheelSensitivity: 1,
              pixelRatio: 'auto'
            });

            var fileElements = $('#bl_cytoscaple_elements_tmp_file').find('.file').children('a').attr('href')
            if (fileElements) {
              $.getJSON(fileElements, function(cytoscapeData) {
                // alert(cytoscapeData.elements)
                // cy.elements = cytoscapeData.elements
                cy.json({elements: cytoscapeData.elements })
              })
            }

            var fileStyles = $('#bl_cytoscaple_style_file').find('.file').children('a').attr('href')
            if (fileStyles) {
              $.getJSON(fileStyles, function() {
                console.log( "success" );
              })
                .done(function(cytoscapeData) {
                  cy.style().fromJson(cytoscapeData.styles).update()
                })
                .fail(function( jqxhr, textStatus, error ) {
                  var err = textStatus + ", " + error;
                  console.log( "Request Failed: " + err );
                })
                .always(function() {
                  console.log( "complete" );
                })
            }
          });

           
       }
   };
})(jQuery, Drupal, drupalSettings);
