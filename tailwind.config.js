// tailwind.config.js
module.exports = {
  content: [
    './src/**/*.{html,js}',  // Include your HTML and JS files
    './public/**/*.{html,js}', // Or wherever your HTML files are
    './**/*.php', // Include PHP files if needed
    './*.php', // Include root PHP files if needed
    './*.phtml', // Include root PHTML files if needed
    './**/*.phtml', // Include PHTML files if needed
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
