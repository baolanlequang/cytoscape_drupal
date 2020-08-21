/**
 * @file
 * Displays cytoscape
 */

 (function ($, Drupal, drupalSettings) {
     'use strict';
     Drupal.behaviors.csdisplay = {
        attach: function(context, settings) {
            $('.cy', context).once('csdisplay').each(function() {
              // console.log(drupalSettings.cytoscape.elements)
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
            
            // $.ajax({
            //   method: "POST",
            //   url: "/blcytoscape",
            //   data: {data: pathname},
            // })
            //   .done(function( successData ) {
            //     console.log(successData);
            //     var cy = cytoscape({
            //       container: $('.cy'),
            //       elements: successData.elements,
            //       style: successData.style,
            //       layout: successData.layout,
            //       // initial viewport state:
            //       zoom: 1,
            //       pan: { x: 0, y: 0 },
            //       // interaction options:
            //       minZoom: 1e-50,
            //       maxZoom: 1e50,
            //       zoomingEnabled: true,
            //       userZoomingEnabled: true,
            //       panningEnabled: true,
            //       userPanningEnabled: true,
            //       boxSelectionEnabled: true,
            //       selectionType: 'single',
            //       touchTapThreshold: 8,
            //       desktopTapThreshold: 4,
            //       autolock: false,
            //       autoungrabify: false,
            //       autounselectify: false,
  
            //       // rendering options:
            //       headless: false,
            //       styleEnabled: true,
            //       hideEdgesOnViewport: false,
            //       textureOnViewport: false,
            //       motionBlur: false,
            //       motionBlurOpacity: 0.2,
            //       wheelSensitivity: 1,
            //       pixelRatio: 'auto'
            //     });
            //   });

            var pathname = window.location.pathname;
            // alert(pathname);
            $.ajax('/blcytoscape'+pathname, {
              success: function(data) {
                console.log(data);
                // var cy = cytoscape({
                //   container: $('.cy'),
                //   elements: data.elements,
                //   style: data.style,
                //   layout: data.layout,
                //   // initial viewport state:
                //   zoom: 1,
                //   pan: { x: 0, y: 0 },
                //   // interaction options:
                //   minZoom: 1e-50,
                //   maxZoom: 1e50,
                //   zoomingEnabled: true,
                //   userZoomingEnabled: true,
                //   panningEnabled: true,
                //   userPanningEnabled: true,
                //   boxSelectionEnabled: true,
                //   selectionType: 'single',
                //   touchTapThreshold: 8,
                //   desktopTapThreshold: 4,
                //   autolock: false,
                //   autoungrabify: false,
                //   autounselectify: false,
  
                //   // rendering options:
                //   headless: false,
                //   styleEnabled: true,
                //   hideEdgesOnViewport: false,
                //   textureOnViewport: false,
                //   motionBlur: false,
                //   motionBlurOpacity: 0.2,
                //   wheelSensitivity: 1,
                //   pixelRatio: 'auto'
                // });

                // var cy = cytoscape({
                //   container: $('.cy'),
                //   elements: drupalSettings.cytoscape.elements,
                //   style: drupalSettings.cytoscape.style,
                //   layout: drupalSettings.cytoscape.layout,
                //   // initial viewport state:
                //   zoom: 1,
                //   pan: { x: 0, y: 0 },
                //   // interaction options:
                //   minZoom: 1e-50,
                //   maxZoom: 1e50,
                //   zoomingEnabled: true,
                //   userZoomingEnabled: true,
                //   panningEnabled: true,
                //   userPanningEnabled: true,
                //   boxSelectionEnabled: true,
                //   selectionType: 'single',
                //   touchTapThreshold: 8,
                //   desktopTapThreshold: 4,
                //   autolock: false,
                //   autoungrabify: false,
                //   autounselectify: false,
    
                //   // rendering options:
                //   headless: false,
                //   styleEnabled: true,
                //   hideEdgesOnViewport: false,
                //   textureOnViewport: false,
                //   motionBlur: false,
                //   motionBlurOpacity: 0.2,
                //   wheelSensitivity: 1,
                //   pixelRatio: 'auto'
                // });

                // cy.style().fromJson(data.style).update()
                // cy.json({elements: data.elements })
                // cy.center()
              },
              error: function() {
                // alert('There was some error performing the AJAX call!');
              }
            });
        }
    };
 })(jQuery, Drupal, drupalSettings);
