function revealStar(Id1, Id2){
    const btn1= document.getElementById(Id1);
    const btn2= document.getElementById(Id2);
    btn1.addEventListener("click", () => {
        btn1.classList.add("d-none");
        btn2.classList.remove("d-none");
    });
}
