import SplitType from 'split-type';
import gsap from 'gsap';

export const initSplitText = () => {
    const splitTitle = new SplitType('.split-text', {
        types: 'chars,lines,words',
        tagName: 'span'
    });

    // Add CSS to handle the overflow
    document.querySelectorAll('.split-text .line').forEach(line => {
        line.style.overflow = 'hidden';
        line.style.display = 'block';
    });

    return splitTitle;
};
