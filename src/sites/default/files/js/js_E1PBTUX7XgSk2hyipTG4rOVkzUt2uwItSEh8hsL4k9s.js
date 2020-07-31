/**
 * @file
 * Displays cytoscape
 */

 (function ($, Drupal, drupalSettings) {
     'use strict';
     Drupal.behaviors.csdisplay = {
        attach: function(context, settings) {
            // $('.cy', context).once('csdisplay').each(function() {
            //   // console.log(drupalSettings.cytoscape.elements)
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
            // });

            $.ajax('/blcytoscape', {
              success: function(data) {
                console.log(data);
                var cy = cytoscape({
                  container: $('.cy'),
                  elements: data.elements,
                  style: data.style,
                  layout: data.layout,
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
              },
              error: function() {
                // alert('There was some error performing the AJAX call!');
              }
            });

            //   var cy = cytoscape({
            //     container: $('.cy'),
            //     elements: [ // list of graph elements to start with
            //       { // node a
            //         data: { id: 'a' }
            //       },
            //       { // node b
            //         data: { id: 'b' }
            //       },
            //       { // edge ab
            //         data: { id: 'ab', source: 'a', target: 'b' }
            //       }
            //     ],
            //     style: [ // the stylesheet for the graph
            //       {
            //         selector: 'node',
            //         style: {
            //           'background-color': '#666',
            //           'label': 'data(id)'
            //         }
            //       },
            //       {
            //         selector: 'edge',
            //         style: {
            //           'width': 3,
            //           'line-color': '#ccc',
            //           'target-arrow-color': '#ccc',
            //           'target-arrow-shape': 'triangle',
            //           'curve-style': 'bezier'
            //         }
            //       }
            //     ],
            //     layout: {
            //       name: 'grid',
            //       rows: 1
            //     } 
            //   });

            // });
        }
    };
 })(jQuery, Drupal, drupalSettings);
;
