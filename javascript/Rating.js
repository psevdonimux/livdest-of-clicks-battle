const design = new Design();
function clickModeRating() {
	design.cssBorder(['color']);
	design.cssMode(['color']);
	document.getElementById('color').style.backgroundImage = `url(${design.getImageMain()}click2.webp)`;
	design.cssShadow(['top1', 'top2', 'top3']);
	design.cssShadowNegative(['top4', 'top5', 'top6', 'top7', 'top8', 'top9', 'top10']);
}
