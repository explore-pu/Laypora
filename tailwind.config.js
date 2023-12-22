const defaultTheme = require('tailwindcss/defaultTheme')


/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./resources/**/*.{js,vue}",
  ],
  theme: {
    extend: {
      spacing: {
        'main': 'calc(100vh - 3rem)'
      }
    },
  },
  plugins: [],
}

