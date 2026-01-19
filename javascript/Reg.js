const design = new Design();
function clickModeReg() {
	const buttons = ['username', 'login', 'password', 'join', 'submit'];
	design.cssMode(['color']);
	design.cssMode(buttons, design.getColorMode(), 'transparent');
	design.cssModePlaceholder(['username', 'login', 'password']);
	design.cssShadowNegative(buttons);
	design.cssShadowBorder(buttons);
	design.cssDropShadow(buttons, '10px');
	document.getElementById('color').style.backgroundImage = `url(${design.getImageMain()}click2.webp)`;
}
