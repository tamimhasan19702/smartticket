/** @format */

const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  content: [
    "./**/*.php", // All PHP files (root + subfolders)
    "./public/js/**/*.js", // JS files (if any dynamic Tailwind use)
    "./**/*.html", // Any HTML files
  ],
  theme: {
    screens: {
      xs: "361px",
      ...defaultTheme.screens,
    },
    extend: {
      colors: {
        "primary-color": "#0fa",
      },
    },
  },
  plugins: [],
};
