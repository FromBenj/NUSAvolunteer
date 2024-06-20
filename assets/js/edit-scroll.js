function onScrollMouse() {
    const windowHeight = window.innerHeight;
    const scrollPosition = window.scrollY;
    if (scrollPosition > windowHeight / 2) {
        const mouse = document.getElementById("scroll-down-mouse");
        mouse.style.opacity = "25%";        
    }
}

window.addEventListener("scroll", onScrollMouse);