/**
 * Javascript to add Chart/Graph Analytics Functionality
 * 
 */


//Random Color Generator Function
function randomColor() {
    var randomColor = Math.floor(Math.random()*16777215).toString(16);
    return randomColor
}

// Function to Create a Bar Graph
function barGraph(dataset, labelset) {
    colors = []
    for(var i = 0; i < dataset.length; i++) {
        var newColor = randomColor()
        colors.push(newColor);
    }
    let ctx = document.getElementById('bar');
    let barChart = new Chart(ctx, {
        type: 'bar',
        labels: labelset,
        data: {
            labels: 'Value',
            backgroundColor: colors,
            data: dataset
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
        colors.push(newColor);
    }
    let ctx = document.getElementById('pie');
    let pieChart = new Chart(ctx, {
        type: 'pie',
        labels: labelset,
        data: {
            labels: 'Value',
            backgroundColor: colors,
            data: dataset
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

var labls = ["Set 1", "Set 2", " Set 3"]
var dat = [4, 13, 7]

barGraph(dat, labls);
pieGraph(dat, labls);


