document.addEventListener('DOMContentLoaded', function () {
    let userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {

		views: {
            timeGridWeek: {
                allDaySlot: false, // Disable all-day track
            },
            timeGridDay: {
                allDaySlot: false, // Disable all-day track
            },
        },
        initialView: 'timeGridWeek',
        timeZone: 'local',
        events: 'src/get-events.php',
        headerToolbar: {
            left: 'prev,today,next',
            center: 'title',
            right: 'timeGridWeek,timeGridDay'
        },
        editable: true,
        selectable: true,
        selectAllow: function(selectInfo) {
			let startDate = moment(selectInfo.start);
			let endDate = moment(selectInfo.end);

            return startDate.isSame(endDate, 'day');
        },
        select: function(info) {


            // Show the Bootstrap modal on date selection
            $('#eventModal').modal('show');

            $('#deleteEventButton').hide();

            // reset evnt handler
            $('#eventModal').off('show.bs.modal');
            // Event handler when the modal is about to be shown
            $('#eventModal').on('show.bs.modal', function() {

                resetFormValidation();

                // Clear the form fields when the modal is shown
                $('#eventTitle').val('');
                $('#eventCategory option:first').prop('selected', true);


                // set color display to first color
                let firstOption = $('#eventCategory option:first');
                firstOption.prop('selected', true);

				// Get the data-color attribute from the first option
                let selectedColor = firstOption.data('color');

				// Set the background color of the selectedColor div
                $('#selectedColor').css('background-color', selectedColor);

				// Show the selectedColor div
                $('#selectedColor').show();
                $('#eventId').val('');
            });

            $('#updateEventBtn').remove();
            $('#saveEventBtn').remove();

            // Add a new button with ID "updateEventBtn"
            let saveButton = $('<button>', {
                type: 'button',
                class: 'btn btn-success',
                id: 'saveEventBtn',
                text: 'Save'
            });

            $('#buttonContainer').append(saveButton);

            // Click event handler for the programmatically added button
            saveButton.on('click', function() {
                // Trigger the form validation manually
                let eventForm = document.getElementById('eventForm');
                if (eventForm.checkValidity()) {
                    // Form is valid, do your code here to add the event
                    console.log('Form is valid. Add the event here.');
                    // For example, you can use $('#eventForm').submit(); to submit the form programmatically
                } else {
                    // Form is invalid, display validation messages
                    eventForm.classList.add('was-validated');
                }
            });

            // Attach the event handler using event delegation
            $('#saveEventBtn').on('click', function() {
                let eventTitle = $('#eventTitle').val();
                let selectedStartDate = $('#eventModal').attr('data-start');
                let selectedEndDate = $('#eventModal').attr('data-end');

                let eventCategory = $('#eventCategory').val()
                let color = $('#eventCategory option:selected').data('color');

                // Send the data to the server via jQuery Ajax
                $.ajax({
                    type: 'POST',
                    url: 'src/add-event.php',
                    data: {
                        title: eventTitle,
                        start: selectedStartDate,
                        end: selectedEndDate,
                        color: color,
                        category: eventCategory

                    },
                    success: function(response) {
                        // Handle the server response if needed
                        console.log('Event saved successfully.');

                        // Close the modal after saving
                        $('#eventModal').modal('hide');
                        // Refresh the calendar to reflect the new event
                        calendar.refetchEvents();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving event:', error);
                        // Handle the error if needed
                    }
                });

            });

            $('.modal-title').text('Add Event');

            // Save the selected start and end dates in ISO 8601 format
            let selectedStartDate = info.startStr;
            let selectedEndDate = info.endStr;

            // Save the selected dates in data attributes of the modal
            $('#eventModal').attr('data-start', selectedStartDate);
            $('#eventModal').attr('data-end', selectedEndDate);

            calendar.refetchEvents()
        },

        eventChange: function(changeInfo) {

            // Get the updated event details
            // let eventTitle = changeInfo.event.title;
            let newStartDate = moment(changeInfo.event.start); // Convert to Moment.js object
            let newEndDate = moment(changeInfo.event.end); // Convert to Moment.js object

            // Check if the event spans multiple days
            if (!newStartDate.isSame(newEndDate, 'day')) {
                alert('Event start and end must reside on the same day.');

                // Revert the event back to its original position
                changeInfo.revert();
            } else {

                moveEvents(changeInfo.event, 'moveEvent');
            }
        },
        eventClick: function(info) {

            resetFormValidation();

            let event = info.event;
            let title = info.event.title;
            let category = event.extendedProps.category;

            // Open the modal
            $('#eventModal').modal('show');

            // Set the modal title,button for update
            $('.modal-title').text('Update Event');

            $('#deleteEventButton').show();

            // Set the current event's values in the modal form fields
            $('#eventId').val(event.id);
            $('#eventTitle').val(title.toString());


            // Get the event background color
            let eventBackgroundColor = info.event.backgroundColor;

            // Set modal event color to whatever the existing events color is
            $('#selectedColor').css('background-color', eventBackgroundColor);

            // Show the selectedColor div
            $('#selectedColor').show();

            // set modal category to be whatever the event category is
            $('#eventCategory').find('option[value="' + category.trim() + '"]').prop('selected', true);

            // do some cleanup so we can make sure we have the right button
            $('#saveEventBtn').remove();
            $('#updateEventBtn').remove();

            // Add a new button with ID "updateEventBtn"
            let updateButton = $('<button>', {
                type: 'button',
                class: 'btn btn-success',
                id: 'updateEventBtn',
                text: 'Update'
            });

            // Append the new button to a container element (e.g., a div with ID "buttonContainer")
            $('#buttonContainer').append(updateButton);


            // Click event handler for the programmatically added button
            updateButton.on('click', function() {
                // Trigger the form validation manually
                let eventForm = document.getElementById('eventForm');
                if (eventForm.checkValidity()) {
                    // Form is valid, do your code here to add the event
                    console.log('Form is valid. Add the event here.');
                    // For example, you can use $('#eventForm').submit(); to submit the form programmatically
                } else {
                    // Form is invalid, display validation messages
                    eventForm.classList.add('was-validated');
                }
            });

            $('#updateEventBtn').on('click', function() {
                let eventTitle = $('#eventTitle').val();
                let eventColor = $('#eventCategory option:selected').data('color');
                let eventCategory = $('#eventCategory').val();
                let eventId = $('#eventId').val();

                // Send the data to the server via jQuery Ajax
                $.ajax({
                    type: 'POST',
                    url: 'src/update-event.php',
                    data: {
                        id: eventId,
                        title: eventTitle,
                        color: eventColor,
                        category: eventCategory
                    },
                    success: function(response) {
                        // Handle the server response if needed
                        console.log('Event updated successfully.');

                        // Close the modal after saving
                        $('#eventModal').modal('hide');
                        // Refresh the calendar to reflect the new event
                        calendar.refetchEvents();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating event:', error);
                        // Handle the error if needed
                    }
                });

            });
        },

    });

    $('#eventModal').on('hide.bs.modal', function() {
        // Reset the modal data when the modal is hidden or closed
        $('#eventTitle').val('');
        $('#eventCategory option:first').prop('selected', true);
        $('#selectedColor').hide();
        $('#eventId').val('');
    });

    $('#selectColor').hide();

    calendar.render();

    // Function to update an event's title on the server
    function moveEvents(event, actionType = null) {

        let eventTitle = event.title;
        let newStartDate = moment(event.start); // Convert to Moment.js object
        let newEndDate = moment(event.end); // Convert to Moment.js object

        // Format the dates as needed (e.g., in ISO 8601 format)
        let formattedStartDate = newStartDate.format('YYYY-MM-DD HH:mm:ss');
        let formattedEndDate = newEndDate.format('YYYY-MM-DD HH:mm:ss');

        // Send the updated event title to the server via jQuery Ajax
        $.ajax({
            type: 'POST',
            url: 'src/update-event.php',
            data: {
                id: event.id,
                title: eventTitle,
                start: formattedStartDate,
                end: formattedEndDate,
                actionType: actionType

            },
            success: function (response) {
                // Handle the server response if needed
                console.log('Event updated successfully.');
            },
            error: function (xhr, status, error) {
                console.error('Error updating event title:', error);
                // Handle the error if needed
            }
        });
    }

    let deleteEventButton = $('#deleteEventButton');

    deleteEventButton.on('click', function(event) {
        event.preventDefault();

        if (confirm("Delete event?")) {
            let eventId = $('#eventId').val();

            $.ajax({
                url: 'src/delete-event.php',
                type: 'POST',
                data: {
                    id: eventId
                },
                success: function(response) {
                    let event = calendar.getEventById(eventId);

                    if (event) {
                        event.remove();

                        console.log('Event deleted successfully.');
                    }
                    // Event deleted successfully, close the modal and refresh the calendar
                    $('#eventModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    // Handle the error case
                    console.log(xhr.responseText);
                }
            });
        }
    });

    // Function to reset the form validation state
    function resetFormValidation() {
        let eventForm = document.getElementById('eventForm');
        eventForm.classList.remove('was-validated');
    }
});
