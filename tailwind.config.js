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
    screens: {
        lg: { max: "1024px" },
        // => @media (max-width: 991px) { ... }
        md: { max: "769px" },
        // => @media (max-width: 767px) { ... }
        sm: { max: "479px" },
        // => @media (max-width: 479px) { ... }
        xs: { max: "320px" },
        // => @media (max-width: 320px) { ... }
      },
    extend: {},
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: [
      "light",
      "dark"
    ]
  }
};
