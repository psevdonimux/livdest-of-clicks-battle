let countClick = gameData.clicks;
let bonusClick = gameData.bonus;
const csrfToken = gameData.csrfToken;
function clickOn() {
	design.cssMode(['color'], color, backgroundColor);
	design.cssMode(['up1', 'up10', 'up100', 'upMax'], color, 'transparent');
	design.getRequest(`php2/Update.php?csrf_token=${encodeURIComponent(csrfToken)}`);
	countClick += bonusClick;
	document.getElementById('count').innerHTML = 'Клики: ' + new Intl.NumberFormat().format(countClick);
	up('max', false);
	const click = document.getElementById('click');
	click.src = design.getImageMain() + skin2;
	new Audio('audio/click.mp3').play();
	setTimeout(() => click.src = design.getImageMain() + skin, 100);
	updateSkin();
}
function clickMode2() {
	design.cssMode(['color'], color, backgroundColor);
	design.cssMode(['up1', 'up10', 'up100', 'upMax'], color, 'transparent');
	design.cssShadow(['count', 'bonus', 'up1', 'up10', 'up100', 'upMax']);
	design.cssShadowBorder(['click', 'up1', 'up10', 'up100', 'upMax']);
	document.getElementById('click').src = design.getImageMain() + skin;
	document.getElementById('color').style.backgroundImage = `url(${design.getImageMain()}click2.webp)`;
	up('max', false);
	design.loaderImage([skin2]);
}
function up(bonus = 'max', update = true) {
	const multiplier = bonusClick * 10;
	const price = bonus === 'max' ? Math.floor(countClick / multiplier) * multiplier : bonus * multiplier;
	const bonusMax = Math.floor(countClick / multiplier);
	const priceMax = bonusMax * multiplier;
	const fmt = new Intl.NumberFormat();
	if (update) {
		if (bonus === 'max' && countClick >= priceMax && bonusMax > 0) {
			countClick -= priceMax;
			bonusClick += bonusMax;
			design.getRequest(`php2/Up.php?up=max&price=${priceMax}&csrf_token=${encodeURIComponent(csrfToken)}`);
		} else if (bonus !== 'max' && countClick >= price) {
			countClick -= price;
			bonusClick += bonus;
			design.getRequest(`php2/Up.php?up=${bonus}&price=${price}&csrf_token=${encodeURIComponent(csrfToken)}`);
		}
	}
	document.getElementById('up1').innerHTML = '+1; ' + fmt.format(multiplier);
	document.getElementById('up10').innerHTML = '+10; ' + fmt.format(10 * multiplier);
	document.getElementById('up100').innerHTML = '+100; ' + fmt.format(100 * multiplier);
	document.getElementById('upMax').innerHTML = `+${fmt.format(Math.floor(countClick / multiplier))}; ${fmt.format(Math.floor(countClick / multiplier) * multiplier)}`;
	document.getElementById('bonus').innerHTML = 'Бонус: ' + fmt.format(bonusClick);
	document.getElementById('count').innerHTML = 'Клики: ' + fmt.format(countClick);
	updateSkin();
}
async function updateSkin() {
	const data = await design.fetchApi('php2/Top.php');
	if (!data) return;
	skin = data.skin;
	skin2 = skin.replace('.webp', '2.webp');
	color = skinColors[skin] || design.getColorMode('#FFFFFF', '#323338');
	design.loaderImage([skin2]);
}
