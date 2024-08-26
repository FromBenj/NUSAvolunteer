/**
 * Author: Alexandre Chabeau
 * License: MIT
 * Contact: alexandrechabeau.pro@gmail.com
 * Original repos: https://github.com/saucyspray/split-text-js
 */
class SplitText {
    constructor(_target) {
        this.result = new Object()
        this.result.originalText = _target.innerText
        this.result.splitted = this.split(_target)
        this.result.words = this.result.splitted.querySelectorAll('.SplitText-wrapper')
        this.result.chars = this.result.splitted.querySelectorAll('.SplitText-char')
        this.result.spaces = this.result.splitted.querySelectorAll('.SplitText-spacer')
        return this.result
    }
    createSpan(_class) {
        let span = document.createElement('span')
        span.style.display = "inline-block"
        span.className = _class
        return span
    }
    split(_target) {
        let containerArray = new Array
        const splittedTarget = _target.innerText.split(' ')
        let counter = splittedTarget.length
        splittedTarget.map(word => {
            const wrapper = this.createSpan('SplitText-wrapper')
            word.split(/(?!^)/).map(char => {
                let el = this.createSpan('SplitText-char')
                el.innerText = char
                wrapper.appendChild(el)
            })
            counter--
            containerArray.push(wrapper)
            if (counter > 0) {
                let space = this.createSpan('SplitText-char SplitText-spacer')
                space.innerHTML = '&nbsp;'
                containerArray.push(space)
            }
        })
        _target.innerHTML = ''
        containerArray.forEach(child => {
            _target.appendChild(child)
        })
        return _target
    }
}
module.exports = {SplitTextJS: SplitText};
