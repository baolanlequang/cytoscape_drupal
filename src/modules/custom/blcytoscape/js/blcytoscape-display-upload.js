/**
 * @file
 * Displays cytoscape
 */

(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.csdisplayUpload = {
       attach: function(context, settings) {
           $('.cy', context).each(function() {
             console.log(drupalSettings.cytoscape.elements)
            //  console.log(context)
            //  console.log(settings)
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
           });

           
       }
   };
})(jQuery, Drupal, drupalSettings);
