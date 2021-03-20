/**
 * Javascript to add Chart/Graph Analytics Functionality
 * 
 */
(function ($, Drupal) {

    //Random Color Generator Function
    function randomColor() {
        var randomColor = Math.floor(Math.random()*16777215).toString(16);
        return randomColor;
    }
    
    // Function to Create a Bar Graph
    function barGraph(dataset, labelset) {
        let colors = [];

        for(var i = 0; i < dataset.length; i++) {
            var newColor = randomColor();
            colors.push("#" + newColor);    //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        let ctx = $('#bar');

        // Original
        // let barChart = new Chart(ctx, {
        //     type: 'bar',
        //     labels: labelset,
        //     data: {
        //         labels: 'Value',
        //         backgroundColor: colors,
        //         data: dataset
        //     },
        //     options: {
        //         legend: { display: false },
        //         title: {
        //           display: true,
        //           text: 'Proof of Concept Bar Chart'
        //         }
        //     }
        // });

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelset,
                datasets: [{
                    data: dataset,
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Proof of Concept Bar Chart'
                }
            }
        });
    }
    
    // Function to Create a Pie Graph
    function pieGraph(dataset, labelset) {
        colors = []
        for(var i = 0; i < dataset.length; i++) {
            var newColor = randomColor()
            colors.push("#" + newColor); //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        let ctx = $('#pie');

        // Original
        // let pieChart = new Chart(ctx, {
        //     type: 'pie',
        //     labels: labelset,
        //     data: {
        //         labels: 'Value',
        //         backgroundColor: colors,
        //         data: dataset
        //     },
        //     options: {
        //         legend: { display: false },
        //         title: {
        //           display: true,
        //           text: 'Proof of Concept Pie Chart'
        //         }
        //     }
        // });

        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labelset,
                datasets: [{
                    data: dataset,
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Proof of Concept Pie Chart'
                }
            }
        });
    }
    
 

    Drupal.behaviors.showGraphs = {
        attach: function(context) {

            var labls = ["Set 1", "Set 2", " Set 3"];
            var dataset = [4, 13, 7];
            
            barGraph(dataset, labls);
            pieGraph(dataset, labls);
          
        }
    }
})(jQuery, Drupal);



