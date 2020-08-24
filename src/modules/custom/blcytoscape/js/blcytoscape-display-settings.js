(function($) {
    // Argument passed from InvokeCommand.
    $.fn.displayCytoScape = function(cytoscapeData) {
      console.log('myAjaxCallback is called.');
      console.log(cytoscapeData);
      var cy = cytoscape({
        container: $('.cy'),
        elements: '',
        style: cytoscapeData.style,
        layout: cytoscapeData.layout,
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
        cy.json({elements: cytoscapeData.elements })
        cy.center()
      // Set textfield's value to the passed arguments.
      $('input#edit-output').attr('value', cytoscapeData.node_title);
    };
  })(jQuery);