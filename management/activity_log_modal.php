<!-- COMMON ACTIVITY LOG MODAL -->

<div class="modal fade" id="commonActivityLogModal" tabindex="-1" aria-labelledby="commonActivityLogModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="commonActivityLogModalLabel">
                    Activity Log
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <div id="commonActivityLogLoading" class="text-center py-5">

                    <div class="spinner-border text-primary"></div>

                    <div class="mt-2">
                        Loading activity log...
                    </div>

                </div>

                <div id="commonActivityLogResult"></div>

            </div>

        </div>

    </div>
</div>


<script>
function showActivityLog(primaryId, pageType, tblname, tblkey) {
    if (!primaryId || !pageType) {
        return;
    }
    $('#commonActivityLogResult').html('');
    $('#commonActivityLogLoading').show();
    $('#commonActivityLogModal').modal('show');
    Swal.fire({
        title: 'Loading Activity Log...',
        text: 'Please wait',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    $.ajax({

        url: 'get_activity_log.php',

        type: 'POST',

        dataType: 'json',

        data: {
            primary_id: primaryId,
            page_type: pageType,
            tblname: tblname,
            tblpkey: tblkey
        },

        success: function(response) {
            Swal.close();
            $('#commonActivityLogLoading').hide();
            if (response.status === 'success') {
                $('#commonActivityLogResult').html(
                    response.html
                );

            } else {
                $('#commonActivityLogResult').html(`
                    <div class="alert alert-warning">
                        ${response.msg}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            $('#commonActivityLogLoading').hide();
            $('#commonActivityLogResult').html(`
                <div class="alert alert-danger">
                    Unable to load activity log.
                </div>
            `);

            console.log(xhr.responseText);
        }

    });
}
</script>