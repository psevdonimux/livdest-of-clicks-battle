const design = new Design();
function clickModeJoin() {
	const buttons = ['login', 'password', 'reg', 'submit'];
	design.cssMode(['color']);
	design.cssMode(buttons, design.getColorMode(), 'transparent');
	design.cssModePlaceholder(['login', 'password']);
	design.cssShadowNegative(buttons);
	design.cssShadowBorder(buttons);
	design.cssDropShadow(buttons, '10px');
	document.getElementById('color').style.backgroundImage = `url(${design.getImageMain()}click2.webp)`;
}
