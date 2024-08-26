import {gsap} from "gsap";
import {SplitTextJS} from "./split-text";


const titles = gsap.utils.toArray('.title-animation');
const tl = gsap.timeline();

titles.push("end")
titles.forEach((title) => {
    if (title !== "end") {
    const splitTitle = new SplitTextJS(title);
    tl
        .from(splitTitle.chars, {
            opacity: 0,
            y: 80,
            rotateX: -90,
            stagger: .02,
        }, "<")

        .to(splitTitle.chars, {
            opacity: 0,
            y: -80,
            rotateX: 90,
            stagger: .02,
        })
    }
})
function afterAnimation() {
    const container = document.getElementById('title-animation-container');
    const navTitle = document.getElementById("anime-title");
    container.remove();
    navTitle.id = "anime-title-appear";
}

tl.eventCallback("onComplete", afterAnimation);



