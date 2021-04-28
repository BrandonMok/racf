/**
 * Javascript to add Chart/Graph Analytics Functionality
 * 
 */
(function ($, Drupal) {

    function randomColor() {
        var randomColor = Math.floor(Math.random()*16777215).toString(16);
        return randomColor;
    }

    function graphColors() {
        return ["#0058B3", "#09335F", "#fcbf49", "#88d498"];
    }


    function pieAllEventsGraph(dataset) {
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
    

    function pieGeneralEventsGraph(dataset) {
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


    function barAttendeesGeneratedGraph(event_data, attendees) {
        let colors = [];

        for(var i = 0; i < event_data.length; i++) {
            var newColor = randomColor();
            colors.push("#" + newColor);
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


    function filterZipCodeAttendeeData(zipCodes, attendeeData) {
        var attend_num = attendeeData.length;
        
        var zips = [], attends = [], prev;

        zipCodes.sort();
        for(var i = 0; i < zipCodes.length; i++) {
            if(zipCodes[i] !== prev) {
                zips.push(String(zipCodes[i]));
                attends.push(1);
            } 
            else {
                attends[attends.length - 1]++;
            }
            prev = zipCodes[i];
        }

        var zip_listed_attends = 0;
        for(var x = 0; x < attends.length; x++) {
            zip_listed_attends = zip_listed_attends + attends[i];
        }
        var zip_unlisted_attends = attend_num - zip_listed_attends;

        zips.push("Unlisted Zip Codes");
        attends.push(zip_unlisted_attends);
        
        let filteredZipAttendeeData = {
            'zip_codes' : zips,
            'attendees' : attends
        }

        return filteredZipAttendeeData;
    }


    function barZipCodeAttendeeGraph(zip_codes, attendees) {
        let colors = [];
        let filteredData = filterZipCodeAttendeeData(zip_codes, attendees);

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


    function barZipCodeGeneralGraph(zip_codes, attendees) {
        let colors = [];
        let filteredData = filterZipCodeAttendeeData(zip_codes, attendees);

        for(var i = 0; i < filteredData.zip_codes.length; i++) {
            var newColor = randomColor();
            colors.push("#" + newColor); 
        }

        let ctx = $('#bar-zipcode-general-attendees');

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
                  text: 'Zip Codes vs. Attendees - General Events'
                }
            }
        });
    }


    Drupal.behaviors.showGraphs = {
        attach: function(context) {

            var events_all = drupalSettings.analytics.graph_data.events_all;
            var events_general = drupalSettings.analytics.graph_data.events_general;
            var attendees = drupalSettings.analytics.graph_data.attendees;
            var generalAttendees = drupalSettings.analytics.graph_data.general_attendees;
            var zip_codes = drupalSettings.analytics.graph_data.zip_codes;
            var zipCodesGeneral = drupalSettings.analytics.graph_data.zip_codes_general;

            pieAllEventsGraph(events_all);
            pieGeneralEventsGraph(events_general);
            barZipCodeAttendeeGraph(zip_codes, attendees);
            barZipCodeGeneralGraph(zipCodesGeneral, generalAttendees);
        }
    }
})(jQuery, Drupal);

