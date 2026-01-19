const design = new Design();
function clickMode() {
	const buttons = ['start', 'skin', 'join', 'reg', 'rating', 'mode'];
	design.cssMode(['color']);
	design.cssMode(buttons, design.isDarkMode() ? '#FFFFFF' : '#000000', 'transparent');
	design.cssShadowNegative(buttons);
	design.cssShadowBorder(buttons);
	design.cssDropShadow(buttons, '5px');
	document.getElementById('mode').innerHTML = design.isDarkMode() ? 'Тёмный' : 'Светлый';
	document.getElementById('color').style.backgroundImage = `url(${design.getImageMain()}click2.webp)`;
}
function startGame() {
	location.href = 'play.php';
}
