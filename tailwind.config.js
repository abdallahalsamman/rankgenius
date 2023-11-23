/** @type {import('tailwindcss').Config} */

const defaultTheme = require("tailwindcss/resolveConfig")(
  require("tailwindcss/defaultConfig"),
).theme;

export default {
  content: [
    // You will probably also need these lines
    "./resources/**/**/*.blade.php",
    "./resources/**/**/*.js",
    "./app/View/Components/**/**/*.php",
    "./app/Livewire/**/**/*.php",
    "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
    "./vendor/usernotnull/tall-toasts/config/**/*.php",
    "./vendor/usernotnull/tall-toasts/resources/views/**/*.blade.php",
  ],
  theme: {
    markdownBase: {
      h1: {
        fontSize: defaultTheme.fontSize["2xl"],
        marginTop: defaultTheme.spacing[5],
      },
      h2: {
        fontSize: defaultTheme.fontSize["xl"],
        marginTop: defaultTheme.spacing[5],
        marginBottom: defaultTheme.spacing[10],
        fontWeight: defaultTheme.fontWeight.medium,
        borderBottomWidth: defaultTheme.borderWidth[4],
        borderColor: defaultTheme.colors.blue[600],
      },
      h3: {
        fontSize: defaultTheme.fontSize["lg"],
        marginTop: defaultTheme.spacing[2.5],
        marginBottom: defaultTheme.spacing[5],
        paddingLeft: defaultTheme.spacing[2.5],
        borderLeftWidth: defaultTheme.borderWidth[2],
        borderColor: defaultTheme.colors.blue[600],
        fontWeight: defaultTheme.fontWeight.medium,
      },
    },

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

    extend: {
      backgroundSize: {
        "300%": "300%",
      },
      animation: {
        ["infinite-slider"]: "infiniteSlider 60s linear infinite",
        gradient: "animatedgradient 6s ease infinite alternate",
      },
      keyframes: {
        infiniteSlider: {
          "0%": { transform: "translateX(0)" },
          "100%": {
            transform: "translateX(calc(-250px * 5))",
          },
        },
        animatedgradient: {
            '0%': { backgroundPosition: '0% 50%' },
            '50%': { backgroundPosition: '100% 50%' },
            '100%': { backgroundPosition: '0% 50%' },
          },
      },
    },
  },
  plugins: [
    require("@geoffcodesthings/tailwind-md-base")(),
    require("daisyui"),
  ],
  daisyui: {
    themes: ["light", "dark"],
  },
};
