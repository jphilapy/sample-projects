<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href='src/assets/js/fullcalendar5/lib/main.css' rel='stylesheet'/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!--    <script src='src/assets/js/fullcalendar5/lib/main.js'></script>-->
    <script src='src/assets/js/fullcalendar-scheduler-6.1.8/dist/index.global.js'></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>




    <style>
        /* Adjust vertical alignment and spacing between the select box and colored box */
        .colored-box-container {
            display: flex;
            align-items: flex-end;
            margin-bottom: 5px;

        }

        .colored-box {
            width: 38px;
            height: 38px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
<div id='calendar'></div>


<!-- Modal -->
<div class="modal" id="eventModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <div class="mb-3">
                        <label for="eventTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="eventTitle" name="eventTitle" required>
                        <input type="hidden" id="eventId">
                    </div>
                    <div class="mb-3 colored-box-container">
          				<div>
                            <label for="eventCategory" class="form-label">Category</label>
                            <select id="eventCategory" class="form-select">
                                <option value="category1" data-color="#ff0000">Category 1</option>
                                <option value="category2" data-color="#00ff00">Category 2</option>
                                <option value="category3" data-color="#0000ff">Category 3</option>
                            </select>
                        </div>
                        <div id="selectedColor" class="colored-box rounded" style="display: none;"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <div class="w-25">
                        <button type="button" class="btn btn-danger" id="deleteEventButton">Delete</button>
                    </div>
                    <div id="buttonContainer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap 5 JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // On page load, set the default color
        setCategoryColor($('#eventCategory').val());

        $('#eventCategory').on('change', function() {
            var selectedCategory = $(this).val();
            setCategoryColor(selectedCategory);
        });
    });

    function setCategoryColor(category) {
        var color = $('#eventCategory option[value="' + category + '"]').data('color');
        $('#selectedColor').css('background-color', color);
        $('#selectedColor').show();
    }
</script>
<script src="src/assets/js/resource.js"></script>
</body>
</html>
