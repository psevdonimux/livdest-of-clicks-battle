class Design {
	getColorMode(light = '#FFFFFF', dark = '#000000', reverse = false) {
		return this.isDarkMode() !== reverse ? light : dark;
	}
	getImageMain() {
		return 'image/' + (this.isDarkMode() ? 'dark' : 'light') + '/';
	}
	async fetchApi(url, options = {}) {
		try {
			const response = await fetch(url, options);
			if (!response.ok) {
				if (response.status === 401) {
					window.location.href = 'join.php';
					return null;
				}
				throw new Error(`HTTP ${response.status}`);
			}
			return await response.json();
		} catch (error) {
			console.error('API Error:', error);
			return null;
		}
	}
	async getRequest(url) {
		return this.fetchApi(url);
	}
	async getRank() {
		const data = await this.fetchApi('php2/Top.php');
		return data ? data.rank : 0;
	}
	isDarkMode() {
		return localStorage.getItem('mode') === 'dark';
	}
	setMode(mode) {
		localStorage.setItem('mode', mode);
	}
	cssMode(ids, custom = '', custom2 = '') {
		const dark = custom2 || (this.isDarkMode() ? '#323338' : '');
		const white = custom || (this.isDarkMode() ? '#FFFFFF' : '');
		ids.forEach(id => {
			const el = document.getElementById(id);
			if (el) {
				el.style.backgroundColor = dark;
				el.style.color = white;
			}
		});
	}
	cssModePlaceholder(ids, custom = '') {
		ids.forEach(id => {
			const el = document.getElementById(id);
			if (el) {
				el.style.setProperty('--' + id, custom || this.getColorMode());
			}
		});
	}
	cssShadow(ids) {
		const c = this.getColorMode();
		const shadow = `-0.5px -0.5px 0 ${c}, 0.5px -0.5px 0 ${c}, -0.5px 0.5px 0 ${c}, 0.5px 0.5px 0 ${c}`;
		ids.forEach(id => {
			const el = document.getElementById(id);
			if (el) el.style.textShadow = shadow;
		});
	}
	cssShadowBorder(ids) {
		const c = this.getColorMode();
		const shadow = `-0.5px -0.5px 0 ${c}, 0.5px -0.5px 0 ${c}, -0.5px 0.5px 0 ${c}, 0.5px 0.5px 0 ${c}`;
		ids.forEach(id => {
			const el = document.getElementById(id);
			if (el) el.style.boxShadow = shadow;
		});
	}
	cssShadowNegative(ids) {
		const c = this.getColorMode('#FFFFFF', '#000000', true);
		const shadow = `-1px -1px 0 ${c}, 1px -1px 0 ${c}, -1px 1px 0 ${c}, 1px 1px 0 ${c}`;
		ids.forEach(id => {
			const el = document.getElementById(id);
			if (el) el.style.textShadow = shadow;
		});
	}
	cssBorder(ids) {
		const c = this.getColorMode();
		ids.forEach(id => {
			const el = document.getElementById(id);
			if (el) el.style.borderColor = c;
		});
	}
	updateMode() {
		this.setMode(this.isDarkMode() ? 'light' : 'dark');
	}
	cssDropShadow(ids, shadow) {
		if (!this.isDarkMode()) return;
		ids.forEach(id => {
			const el = document.getElementById(id);
			if (el) el.style.filter = `drop-shadow(0px 0px ${shadow} #FFFFFF)`;
		});
	}
	imageMode(ids, images) {
		const path = this.getImageMain();
		ids.forEach((id, i) => {
			const el = document.getElementById(id);
			if (el) el.src = path + images[i];
		});
	}
	loaderImage(images) {
		const path = this.getImageMain();
		const loader = document.getElementById('loader');
		if (loader) {
			images.forEach(img => loader.src = path + img);
		}
	}
}
