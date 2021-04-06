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

    function graphColors() {
        return ["#0058B3", "#09335F", "#fcbf49", "#88d498"];
    }
    
    //bar-all-events
    function barAllEventsGraph(dataset) {
        let colors = [];

        for(var i = 0; i < dataset.length; i++) {
            var newColor = randomColor();
            colors.push("#" + newColor);    //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        let ctx = $('#bar-all-events');

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Passes Generated", "Passes Scanned"],
                datasets: [{
                    data: dataset,
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Generated Passes vs. Scanned Passes - All Events'
                }
            }
        });
    }

    //pie-all-events
    function pieAllEventsGraph(dataset) {
        // colors = []
        // for(var i = 0; i < dataset.length; i++) {
        //     var newColor = randomColor()
        //     colors.push("#" + newColor); //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        // }

        let colors = graphColors();

        let ctx = $('#pie-all-events');

        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Passes Generated", "Passes Scanned"],
                datasets: [{
                    data: dataset,
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Generated Passes vs. Scanned Passes - All Events'
                }
            }
        });
    }
    
    //bar-general-events
    function barGeneralEventsGraph(dataset) {
        let colors = [];

        for(var i = 0; i < dataset.length; i++) {
            var newColor = randomColor();
            colors.push("#" + newColor);    //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        let ctx = $('#bar-general-events');

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Passes Generated", "Passes Scanned"],
                datasets: [{
                    data: dataset,
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Generated Passes vs. Scanned Passes - General Events'
                }
            }
        });
    }

    //pie-general-events
    function pieGeneralEventsGraph(dataset) {
        // colors = []
        // for(var i = 0; i < dataset.length; i++) {
        //     var newColor = randomColor()
        //     colors.push("#" + newColor); //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        // }

        let colors = graphColors();

        let ctx = $('#pie-general-events');

        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Passes Generated", "Passes Scanned"],
                datasets: [{
                    data: dataset,
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Generated Passes vs. Scanned Passes - General Events'
                }
            }
        });
    }


    //bar-generated-attendees
    function barAttendeesGeneratedGraph(event_data, attendees) {
        let colors = [];

        for(var i = 0; i < event_data.length; i++) {
            var newColor = randomColor();
            colors.push("#" + newColor);    //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        let ctx = $('#bar-generated-attendees');

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Passes Generated", "Attendees"],
                datasets: [{
                    data: [event_data[0], attendees.length],
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Generated Passes vs. Attendees - All Events'
                }
            }
        });
    }

    //pie-generated-attendees
    function pieAttendeesGeneratedGraph(event_data, attendees) {
        colors = []
        for(var i = 0; i < event_data.length; i++) {
            var newColor = randomColor()
            colors.push("#" + newColor); //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        let ctx = $('#pie-generated-attendees');

        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Passes Generated", "Passes Scanned"],
                datasets: [{
                    data: [event_data[0], attendees.length],
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Generated Passes vs. Attendees - All Events'
                }
            }
        });
    }

    //bar-scanned-attendees
    function barAttendeesScannedGraph(event_data, attendees) {
        let colors = [];

        for(var i = 0; i < event_data.length; i++) {
            var newColor = randomColor();
            colors.push("#" + newColor);    //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        let ctx = $('#bar-scanned-attendees');

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Passes Scanned", "Attendees"],
                datasets: [{
                    data: [event_data[1], attendees.length],
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Scanned Passes vs. Attendees - All Events'
                }
            }
        });
    }

    //pie-scanned-attendees
    function pieAttendeesScannedGraph(event_data, attendees) {
        colors = []
        for(var i = 0; i < event_data.length; i++) {
            var newColor = randomColor()
            colors.push("#" + newColor); //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        let ctx = $('#pie-scanned-attendees');

        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Passes Scanned", "Attendees"],
                datasets: [{
                    data: [event_data[1], attendees.length],
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Scanned Passes vs. Attendees - All Events'
                }
            }
        });
    }

    //Filters Zip Code and Attendee Data to return number of Attendees from each Zip Code
    function filterZipCodeAttendeeData(zipCodes, attendeeData) {
        var attend_num = attendeeData.length
        
        var zips = [], attends = [], prev;

        zipCodes.sort()
        for(var i = 0; i < zipCodes.length; i++) {
            if(zipCodes[i] !== prev) {
                zips.push(String(zipCodes[i]))
                attends.push(1)
            } 
            else {
                attends[attends.length - 1]++;
            }
            prev = zipCodes[i]
        }

        var zip_listed_attends = 0
        for(var x = 0; x < attends.length; x++) {
            zip_listed_attends = zip_listed_attends + attends[i]
        }
        var zip_unlisted_attends = attend_num - zip_listed_attends

        zips.push("Unlisted Zip Codes")
        attends.push(zip_unlisted_attends)
        
        let filteredZipAttendeeData = {
            'zip_codes' : zips,
            'attendees' : attends
        }

        return filteredZipAttendeeData
    }

    //bar-zipcode-attendees
    function barZipCodeAttendeeGraph(zip_codes, attendees) {
        let colors = [];
        let filteredData = filterZipCodeAttendeeData(zip_codes, attendees)

        for(var i = 0; i < filteredData.zip_codes.length; i++) {
            var newColor = randomColor();
            colors.push("#" + newColor); 
        }

        let ctx = $('#bar-zipcode-attendees');

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: filteredData.zip_codes,
                datasets: [{
                    data: filteredData.attendees,
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Zip Codes vs. Attendees - All Events'
                }
            }
        });
    }


    //pie-zipcode-attendees
    function pieZipCodeAttendeeGraph(zip_codes, attendees) {
        colors = []
        let filteredData = filterZipCodeAttendeeData(zip_codes, attendees)
        
        for(var i = 0; i < filteredData.zip_codes.length; i++) {
            var newColor = randomColor()
            colors.push("#" + newColor); //needed # to make color understandable (idk how it worked beforehand w/o # sign)
        }
        
        let ctx = $('#pie-zipcode-attendees');

        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: filteredData.zip_codes,
                datasets: [{
                    data: filteredData.attendees,
                    backgroundColor: colors,
                }]
            },
            options: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'Zip Codes vs. Attendees - All Events'
                }
            }
        });
    }

    Drupal.behaviors.showGraphs = {
        attach: function(context) {

            var events_all = drupalSettings.analytics.graph_data.events_all;

            var events_general = drupalSettings.analytics.graph_data.events_general;

            var attendees = drupalSettings.analytics.graph_data.attendees;

            var zip_codes = drupalSettings.analytics.graph_data.zip_codes;

            // barAllEventsGraph(events_all)
            pieAllEventsGraph(events_all);

            // barGeneralEventsGraph(events_general);
            pieGeneralEventsGraph(events_general);
            
            // barAttendeesGeneratedGraph(events_all, attendees);
            // pieAttendeesGeneratedGraph(events_all, attendees);
            
            // barAttendeesScannedGraph(events_all, attendees);
            // pieAttendeesScannedGraph(events_all, attendees);

            barZipCodeAttendeeGraph(zip_codes, attendees);
            pieZipCodeAttendeeGraph(zip_codes, attendees);
        }
    }
})(jQuery, Drupal);

