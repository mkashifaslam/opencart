function makeSEOlink(firstField, intoField) {
	var ru2en = {
		ru_str : "АБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдежзийклмнопрстуфхцчшщъыьэюя ./",
		en_str : ['a','b','v','g','d','e','j','z','i','y','k','l','m','n','o','p','r',
			's','t','u','f','h','ts','ch','sh','sch','','yi','','e','yu','ya','a','b',
			'v','g','d','e','j','z','i','y','k','l','m','n','o','p','r','s','t','u',
			'f','h','ts','ch','sh','sch','y','yi','','e','yu','ya','_','','_'],
		translit : function(org_str) {
			var tmp_str = [];
			for(var i = 0, l = org_str.length; i < l; i++) {
				var s = org_str.charAt(i), n = this.ru_str.indexOf(s);
				if(n >= 0) { tmp_str[tmp_str.length] = this.en_str[n]; }
				else { tmp_str[tmp_str.length] = s; }
			}
			return tmp_str.join("");
		}
	}
	var s = ru2en.translit(document.getElementById(firstField).value);
	s = s.replace(/[^0-9a-zA-Z]+/g, "-");
	s = s.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	document.getElementById(intoField).value = s.toLowerCase();
}
