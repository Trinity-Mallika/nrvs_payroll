<?php
switch ($action) {
    case '1':
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert"  id="myElem">
                <strong><i class="bi bi-check-circle-fill" ></i> Record Inserted !!</strong> Successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        break;
    case '2':
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert" id="update">
                    <strong><i class="bi bi-check-circle-fill"></i> Record Updated !! </strong> Successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        break;
    case '3':
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="delete">
                        <strong><i class="bi bi-check-circle-fill"></i> Record Deleted !! </strong> Successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        break;
    case '4':
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert" id="duplicate" >
                        <strong><i class="bi bi-exclamation-triangle-fill"></i> Duplicate Record !! </strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        break;
} ?>
<script>
    setTimeout(function() {
        $('#myElem').hide()
    }, 2000);
    setTimeout(function() {
        $('#update').hide();
    }, 2000);
    setTimeout(function() {
        $('#delete').hide()
    }, 2000);
    setTimeout(function() {
        $('#duplicate').hide()
    }, 2000);
    setTimeout(function() {
        $('#al_amount').hide()
    }, 2000);
    setTimeout(function() {
        $('#monthcls').hide()
    }, 2000);
    setTimeout(function() {
        $('#editamt').hide()
    }, 2000);
    setTimeout(function() {
        $('#odometer').hide()
    }, 2000);
    setTimeout(function() {
        $('#odometer1').hide()
    }, 2000);
</script>