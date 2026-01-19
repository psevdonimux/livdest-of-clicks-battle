const design = new Design();
const color = design.getColorMode('#FFFFFF', '#000000');
const skinList = ['click.webp', 'silver.webp', 'bronze.webp', 'gold.webp'];
const rankSkins = {1: 'gold.webp', 2: 'silver.webp', 3: 'bronze.webp'};
let currentRank = 0;
let selectedSkin = 'click.webp';
async function clickMode() {
	design.cssMode(['color']);
	design.cssMode(['left', 'right'], color, 'transparent');
	design.cssShadow(['page', 'left', 'right']);
	design.cssShadowBorder(['left', 'right']);
	const data = await design.fetchApi('php2/Skin.php?type=select');
	if (data) {
		selectedSkin = data.selected;
		currentRank = data.rank || 0;
	}
	design.imageMode(
		['skin1', 'skin2', 'skin3', 'skin4', 'skin5', 'skin6', 'skin7', 'skin8', 'skin9'],
		skinList
	);
	updateSkinDisplay();
	document.getElementById('color').style.backgroundImage = `url(${design.getImageMain()}click2.webp)`;
}
function updateSkinDisplay() {
	for (let i = 1; i <= 9; i++) {
		const el = document.getElementById('skin' + i);
		if (!el) continue;
		if (i > skinList.length) {
			el.style.display = 'none';
			continue;
		}
		const skinName = skinList[i - 1];
		const isSelected = skinName === selectedSkin;
		const isRankSkin = Object.values(rankSkins).includes(skinName);
		const canUse = !isRankSkin || rankSkins[currentRank] === skinName;
		el.style.border = isSelected ? `5px double ${color}` : '0px';
		el.style.opacity = canUse ? '1' : '0.3';
		el.style.cursor = canUse ? 'pointer' : 'not-allowed';
	}
}
async function selectSkin(skinIndex) {
	if (skinIndex > skinList.length) return;
	const skinName = skinList[skinIndex - 1];
	const isRankSkin = Object.values(rankSkins).includes(skinName);
	if (isRankSkin && rankSkins[currentRank] !== skinName) {
		const place = skinName === 'gold.webp' ? '1' : skinName === 'silver.webp' ? '2' : '3';
		alert('Этот скин доступен только для ' + place + ' места в рейтинге');
		return;
	}
	const response = await design.fetchApi(`php2/Skin.php?type=set&skin=${encodeURIComponent(skinName)}&csrf_token=${encodeURIComponent(csrfToken)}`);
	if (response && response.success) {
		selectedSkin = skinName;
		updateSkinDisplay();
	} else if (response && response.error) {
		alert(response.error);
	}
}
