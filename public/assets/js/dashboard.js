function slugify(content) {
	return content.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
}