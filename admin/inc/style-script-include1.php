<style>
    .toggle-icon {
        cursor: pointer;
        user-select: none;
        margin-right: 4px;
    }

    .nested-list,
    .nested-list ul {
        list-style: none;
        margin: 0;
        padding-left: 1rem;
        position: relative;
    }

    .nested-list ul {
        border-left: 1px solid black;
        margin-left: 0.5rem;
    }

    .child-node {
        position: relative;
    }

    .child-node::after {
        content: '';
        position: absolute;
        top: 11px;
        left: -32px;
        width: 35px;
        height: 1px;
        background: #999;
    }

    .main-node {
        position: relative;
    }

    .main-node::after {
        content: '';
        position: absolute;
        top: 11px;
        left: -17px;
        width: 21px;
        height: 1px;
        background: #999;
    }

    .form-check {
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 0.25rem;
    }
</style>

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const allCheckbox = document.getElementById("all");

        if (!allCheckbox) return;

        allCheckbox.addEventListener("change", function() {
            const unitCheckboxes = document.querySelectorAll(
                '.tree-dropdown input[type="checkbox"][name="unit_id[]"]'
            );

            if (this.checked) {
                unitCheckboxes.forEach(cb => {
                    cb.checked = false;
                    cb.disabled = true;
                });
            } else {
                unitCheckboxes.forEach(cb => {
                    cb.disabled = false;
                });
            }
        });

    });
</script>