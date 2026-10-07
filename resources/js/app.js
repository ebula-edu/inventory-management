const inventorySubPages = [
    'inventory',
    'products',
    'stock',
    'stock-in',
    'stock-out',
    'inventory-management',
    'low-stock'
];

window.showPage = function(pageID) {
    const pages = document.querySelectorAll(".page");
    pages.forEach(function(p) {
        p.classList.remove("active");
    });

    const targetPage = document.getElementById(pageID);
    if (targetPage) {
        targetPage.classList.add("active");
    }

    window.closeMegaMenu();
    updateNavActiveStates(pageID);
    window.scrollTo({ top: 0, behavior: "smooth" });
};

function updateNavActiveStates(pageID) {
    const isInventory = inventorySubPages.includes(pageID);

    document.querySelectorAll(".desktop-nav .nav-link").forEach(function(link) {
        const pageAttr = link.getAttribute("data-page");
        if (link.id === "desktopInventoryTrigger") {
            link.classList.toggle("active", isInventory);
        } else {
            link.classList.toggle("active", pageAttr === pageID);
        }
    });

    document.querySelectorAll("#megaMenu a").forEach(function(link) {
        link.classList.toggle("active", link.getAttribute("data-page") === pageID);
    });

    document.querySelectorAll(".sidebar .side-link").forEach(function(link) {
        const pageAttr = link.getAttribute("data-page");
        link.classList.toggle("active", pageAttr === pageID);
    });

    document.querySelectorAll(".side-dropdown a").forEach(function(link) {
        link.classList.toggle("active", link.getAttribute("data-page") === pageID);
    });

    const tabs = document.querySelectorAll(".mobile-tabs .tab");
    tabs.forEach(function(tab) {
        const pageAttr = tab.getAttribute("data-page");
        if (pageAttr === "inventory") {
            tab.classList.toggle("active", isInventory);
        } else {
            tab.classList.toggle("active", pageAttr === pageID);
        }
    });
}

window.openSidebar = function() {
    document.getElementById("sidebar").classList.add("active");
    document.getElementById("overlay").classList.add("active");
    document.body.classList.add("sidebar-open");
};

window.closeSidebar = function() {
    document.getElementById("sidebar").classList.remove("active");
    document.getElementById("overlay").classList.remove("active");
    document.body.classList.remove("sidebar-open");
};

window.toggleSideInventory = function() {
    const menu = document.getElementById("sideInventory");
    const arrow = document.getElementById("sideArrow");
    const isOpen = menu.classList.toggle("show");
    if (arrow) {
        arrow.className = isOpen ? "fa-solid fa-chevron-up" : "fa-solid fa-chevron-down";
    }
};

window.toggleMegaMenu = function(event) {
    if (event) {
        event.stopPropagation();
    }
    const mega = document.getElementById("megaMenu");
    const parentItem = document.getElementById("desktopInventoryItem");
    const isOpen = mega.classList.toggle("show");
    if (parentItem) {
        parentItem.classList.toggle("open", isOpen);
    }
};

window.closeMegaMenu = function() {
    const mega = document.getElementById("megaMenu");
    const parentItem = document.getElementById("desktopInventoryItem");
    if (mega) {
        mega.classList.remove("show");
    }
    if (parentItem) {
        parentItem.classList.remove("open");
    }
};

document.addEventListener("click", function(event) {
    const mega = document.getElementById("megaMenu");
    const inventoryItem = document.getElementById("desktopInventoryItem");

    if (mega && mega.classList.contains("show")) {
        if (inventoryItem && !inventoryItem.contains(event.target)) {
            window.closeMegaMenu();
        }
    }
});

document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
        window.closeSidebar();
        window.closeMegaMenu();
    }
});