document.addEventListener("DOMContentLoaded", () => {
    const menuItems = document.querySelectorAll(".menu-title");
    const toggle = document.querySelector(".menu-toggle");
    const sidebar = document.getElementById("sidebar");
    const submenuLinks = document.querySelectorAll(".submenu li");

    menuItems.forEach(menu => {
        menu.addEventListener("click", (e)=>{
            e.stopPropagation();
            const parent = menu.parentElement;

            document.querySelectorAll(".menu-item").forEach(item => {
                if(item !== parent) item.classList.remove("active-menu");
            });

            parent.classList.toggle("active-menu");
        });
    });

    menuItems.forEach(menu => {
        menu.addEventListener("click", (e)=>{
            e.stopPropagation();
            const parent = menu.parentElement;
            parent.classList.toggle("active");
        });
    });

    toggle.addEventListener("click", (e)=>{
        e.stopPropagation();
        sidebar.classList.toggle("active");
    });

    document.addEventListener("click", (e)=>{
        if(sidebar.classList.contains("active") && !sidebar.contains(e.target) && !toggle.contains(e.target)){
            sidebar.classList.remove("active");
        }
    });
});