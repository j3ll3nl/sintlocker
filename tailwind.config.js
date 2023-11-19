/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          light: "#fefcbf", // For lighter primary color
          DEFAULT: "#b7791f", // Normal primary color
          dark: "#744210", // Used for hover, active, etc.
        },
      },
    },
  },
  plugins: [require("kutty")],
}

