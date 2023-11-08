/** @type {import('tailwindcss').Config} */
export default {
  content: [
    // You will probably also need these lines
    "./resources/**/**/*.blade.php",
    "./resources/**/**/*.js",
    "./app/View/Components/**/**/*.php",
    "./app/Livewire/**/**/*.php",
    "./vendor/robsontenorio/mary/src/View/Components/**/*.php"
  ],
  theme: {
    extend: {},
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: [
      "light",
    ]
  }
};