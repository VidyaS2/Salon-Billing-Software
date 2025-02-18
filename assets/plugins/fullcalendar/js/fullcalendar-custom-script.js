$(document).ready(function() {
  $('#calendar').fullCalendar({
    // Other options...

    events: function(start, end, timezone, callback) {
      $.ajax({
        url: '/getappointments.php', // Path to server-side script that returns appointment data
        dataType: 'json',
        success: function(response) {
          console.log(response); // Log the received data
          var events = [];
          // Format response data into FullCalendar event objects
          for (var i = 0; i < response.length; i++) {
            events.push({
              title: response[i].title,
              start: response[i].start,
              end: response[i].end
              // You can add more properties here as needed
            });
          }
          callback(events);
        }
      });
    }
  });
});
