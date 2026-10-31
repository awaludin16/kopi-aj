// Begin: Dark Mode
const toggleDarkMode = document.getElementById("toggleDarkMode");
toggleDarkMode.addEventListener("click", function (e) {
    document.documentElement.classList.toggle("dark");
});
// End: Dark Mode

// Begin: Dropdown Toggle
const dropdown = document.querySelector(".dropdown");
const dropdownToggle = dropdown.querySelector(".dropdown-toggle");
const dropdownMenu = dropdown.querySelector(".dropdown-menu");

// Tambahkan event listener untuk toggle dropdown
dropdownToggle.addEventListener("click", () => {
    dropdownMenu.classList.toggle("hidden");
});

// Tutup dropdown jika klik di luar
document.addEventListener("click", (e) => {
    if (
        !dropdownToggle.contains(e.target) &&
        !dropdownMenu.contains(e.target)
    ) {
        dropdownMenu.classList.add("hidden");
    }
});
// End: Deopdown Toggle

// start: Sidebar
const sidebarToggle = document.querySelector(".sidebar-toggle");
const sidebarOverlay = document.querySelector(".sidebar-overlay");
const sidebarMenu = document.querySelector(".sidebar-menu");
const main = document.querySelector(".main");
if (window.innerWidth < 768) {
    main.classList.toggle("active");
    sidebarOverlay.classList.toggle("hidden");
    sidebarMenu.classList.toggle("-translate-x-full");
}
sidebarToggle.addEventListener("click", function (e) {
    e.preventDefault();
    main.classList.toggle("active");
    sidebarOverlay.classList.toggle("hidden");
    sidebarMenu.classList.toggle("-translate-x-full");
});
sidebarOverlay.addEventListener("click", function (e) {
    e.preventDefault();
    main.classList.add("active");
    sidebarOverlay.classList.add("hidden");
    sidebarMenu.classList.add("-translate-x-full");
});
document.querySelectorAll(".sidebar-dropdown-toggle").forEach(function (item) {
    item.addEventListener("click", function (e) {
        e.preventDefault();
        const parent = item.closest(".group");
        if (parent.classList.contains("selected")) {
            parent.classList.remove("selected");
        } else {
            document
                .querySelectorAll(".sidebar-dropdown-toggle")
                .forEach(function (i) {
                    i.closest(".group").classList.remove("selected");
                });
            parent.classList.add("selected");
        }
    });
});
// end: Sidebar

// start: Popper
const popperInstance = {};
document.querySelectorAll(".dropdown").forEach(function (item, index) {
    const popperId = "popper-" + index;
    const toggle = item.querySelector(".dropdown-toggle");
    const menu = item.querySelector(".dropdown-menu");
    menu.dataset.popperId = popperId;
    popperInstance[popperId] = Popper.createPopper(toggle, menu, {
        modifiers: [
            {
                name: "offset",
                options: {
                    offset: [0, 8],
                },
            },
            {
                name: "preventOverflow",
                options: {
                    padding: 24,
                },
            },
        ],
        placement: "bottom-end",
    });
});
document.addEventListener("click", function (e) {
    const toggle = e.target.closest(".dropdown-toggle");
    const menu = e.target.closest(".dropdown-menu");
    if (toggle) {
        const menuEl = toggle
            .closest(".dropdown")
            .querySelector(".dropdown-menu");
        const popperId = menuEl.dataset.popperId;
        if (menuEl.classList.contains("hidden")) {
            hideDropdown();
            menuEl.classList.remove("hidden");
            showPopper(popperId);
        } else {
            menuEl.classList.add("hidden");
            hidePopper(popperId);
        }
    } else if (!menu) {
        hideDropdown();
    }
});

function hideDropdown() {
    document.querySelectorAll(".dropdown-menu").forEach(function (item) {
        item.classList.add("hidden");
    });
}
function showPopper(popperId) {
    popperInstance[popperId].setOptions(function (options) {
        return {
            ...options,
            modifiers: [
                ...options.modifiers,
                { name: "eventListeners", enabled: true },
            ],
        };
    });
    popperInstance[popperId].update();
}
function hidePopper(popperId) {
    popperInstance[popperId].setOptions(function (options) {
        return {
            ...options,
            modifiers: [
                ...options.modifiers,
                { name: "eventListeners", enabled: false },
            ],
        };
    });
}
// end: Popper

// start: Tab
document.querySelectorAll("[data-tab]").forEach(function (item) {
    item.addEventListener("click", function (e) {
        e.preventDefault();
        const tab = item.dataset.tab;
        const page = item.dataset.tabPage;
        const target = document.querySelector(
            '[data-tab-for="' + tab + '"][data-page="' + page + '"]'
        );
        document
            .querySelectorAll('[data-tab="' + tab + '"]')
            .forEach(function (i) {
                i.classList.remove("active");
            });
        document
            .querySelectorAll('[data-tab-for="' + tab + '"]')
            .forEach(function (i) {
                i.classList.add("hidden");
            });
        item.classList.add("active");
        target.classList.remove("hidden");
    });
});
// end: Tab

// start: Menu Preview Image
const imageInput = document.getElementById("image");
const previewWrapper = document.getElementById("image-preview-wrapper");
const previewImage = document.getElementById("image-preview");
const removeBtn = document.getElementById("remove-image");
const removeInput = document.getElementById("remove_image");

// realtime preview
imageInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewWrapper.classList.remove("hidden");
            removeInput.value = 0;
        };
        reader.readAsDataURL(file);
    }
});

// hapus gambar
removeBtn.addEventListener("click", function () {
    previewImage.src = "";
    previewWrapper.classList.add("hidden");
    imageInput.value = "";
    removeInput.value = 1;
});
// end: Menu Preview Image
