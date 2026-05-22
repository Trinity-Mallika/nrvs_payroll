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
        document.querySelectorAll(".tree-dropdown").forEach(tree => {
            const searchInput = tree.querySelector(".tree-search");
            const allCheckbox = tree.querySelector(".tree-check-all");
            const countDisplay = tree.querySelector(".tree-count");
            const checkboxes = tree.querySelectorAll("input[type='checkbox']:not(.tree-check-all)");

            tree.querySelectorAll('.toggle-icon').forEach(icon => {
                icon.addEventListener('click', e => {
                    const ul = icon.closest('li')?.querySelector(':scope > ul');
                    if (ul) {
                        ul.style.display = ul.style.display === 'none' ? 'block' : 'none';
                        icon.textContent = ul.style.display === 'none' ? '▸' : '▾';
                    }
                    e.stopPropagation();
                });
            });

            tree.querySelectorAll('li > div.form-check, li > div.bg-body-tertiary').forEach(div => {
                div.addEventListener('click', function(e) {
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') return;
                    const li = this.closest('li');
                    const ul = li.querySelector(':scope > ul');
                    if (ul) {
                        const toggleIcon = this.querySelector('.toggle-icon');
                        const isVisible = ul.style.display !== 'none';
                        ul.style.display = isVisible ? 'none' : 'block';
                        if (toggleIcon) toggleIcon.textContent = isVisible ? '▸' : '▾';
                    }
                });
            });

            tree.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.addEventListener('click', e => e.stopPropagation());
            });

            if (allCheckbox) {
                allCheckbox.addEventListener("change", function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateCheckedCount();
                });
            }

            function toggleChildren(checkbox, checked) {
                const parentLi = checkbox.closest("li");
                if (!parentLi) return;
                const childCheckboxes = parentLi.querySelectorAll("ul input[type='checkbox']");
                childCheckboxes.forEach(cb => cb.checked = checked);
            }

            function updateParentState(child) {
                let parentUl = child.closest("ul");

                while (parentUl && parentUl.closest("li")) {
                    const parentLi = parentUl.closest("li");

                    // Find direct child <ul>'s checkboxes only
                    const directChildCheckboxes = Array.from(
                        parentLi.querySelectorAll(":scope > ul > li > div > input[type='checkbox']")
                    );

                    const allChecked = directChildCheckboxes.every(cb => cb.checked);
                    const anyChecked = directChildCheckboxes.some(cb => cb.checked);

                    const parentCheckbox = parentLi.querySelector(":scope > div > input[type='checkbox']:not(.tree-check-all)");

                    if (parentCheckbox) {
                        parentCheckbox.checked = allChecked;
                        parentCheckbox.indeterminate = false; // Not using partial check
                    }

                    parentUl = parentUl.parentElement.closest("ul");
                }
            }


            function updateCheckedCount() {
                const siteCheckboxes = tree.querySelectorAll('.child-node input[type="checkbox"]');
                const checkedCount = Array.from(siteCheckboxes).filter(cb => cb.checked).length;
                if (countDisplay) countDisplay.textContent = `(${checkedCount})`;
            }

            checkboxes.forEach(cb => {
                cb.addEventListener("change", function() {
                    toggleChildren(cb, cb.checked);
                    updateParentState(cb);
                    updateCheckedCount();
                });
            });

            updateCheckedCount();

            if (searchInput) {
                searchInput.addEventListener("input", function() {
                    const query = this.value.trim().toLowerCase();
                    const allLis = tree.querySelectorAll(".nested-list li");
                    allLis.forEach(li => li.style.display = "none");

                    if (query === "" || query === "all") {
                        allLis.forEach(li => li.style.display = "");
                        return;
                    }

                    tree.querySelectorAll(".nested-list label").forEach(label => {
                        const text = label.textContent.trim().toLowerCase();
                        if (text.includes(query)) {
                            const matchedLi = label.closest("li");
                            if (!matchedLi) return;
                            matchedLi.style.display = "";
                            matchedLi.querySelectorAll("li").forEach(child => child.style.display = "");
                            let parent = matchedLi.parentElement;
                            while (parent && !parent.classList.contains("nested-list")) {
                                if (["UL", "LI"].includes(parent.tagName)) parent.style.display = "";
                                parent = parent.parentElement;
                            }
                        }
                    });
                });
            }
        });
    });
</script>