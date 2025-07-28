export default function (eleventyConfig) {
	// Output directory: _site

	// Copy `img/` to `_site/img/`
	eleventyConfig.addPassthroughCopy("theme");
};
