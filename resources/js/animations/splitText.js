import SplitType from 'split-type';

export const initSplitText = () => {
    const el = document.querySelector('.split-text');
    if (!el) return null;

    const splitTitle = new SplitType('.split-text', {
        types: 'chars,lines,words',
        tagName: 'span',
    });

    // Pastikan lines ada overflow hidden supaya animation naik cantik
    document.querySelectorAll('.split-text .line').forEach((line) => {
        line.style.overflow = 'hidden';
        line.style.display = 'block';
    });

    return splitTitle;
};
