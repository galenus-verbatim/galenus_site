import fs from 'fs';
import path from 'path';

import cssnano from 'cssnano';
import postcss from 'postcss';
import tailwindcss from '@tailwindcss/postcss';

export default function (eleventyConfig) {
	eleventyConfig.setInputDirectory("src");
	eleventyConfig.setOutputDirectory("dist");

	// Copy `img/` to `_site/img/`
	eleventyConfig.addPassthroughCopy("./src/theme");

	// compile tailwind before eleventy processes the files
	// see https://www.humankode.com/eleventy/how-to-set-up-tailwind-4-with-eleventy-3/
	eleventyConfig.on('eleventy.before', async () => {
		const tailwindInputPath = path.resolve('./src/galenus-tailwind.css');

		const tailwindOutputPath = './dist/assets/styles/index.css';

		const cssContent = fs.readFileSync(tailwindInputPath, 'utf8');

		const outputDir = path.dirname(tailwindOutputPath);
		if (!fs.existsSync(outputDir)) {
			fs.mkdirSync(outputDir, { recursive: true });
		}

		const result = await processor.process(cssContent, {
			from: tailwindInputPath,
			to: tailwindOutputPath,
		});

		fs.writeFileSync(tailwindOutputPath, result.css);
	});

	const processor = postcss([
		//compile tailwind
		tailwindcss(),

		//minify tailwind css
		cssnano({
			preset: 'default',
		}),
	]);

	return {
		dir: { input: 'src', output: 'dist' },
	};
};
