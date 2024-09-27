const plugin = require('tailwindcss/plugin')

module.exports = {
  purge: [
     './resources/**/*.blade.php',
     './resources/**/*.js',
     './resources/**/*.vue',
     './app/Http/Controllers/**/*.php'
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
    variants: {
        extend: {
            borderColor: ['label-checked'], // you need add new variant to a property you want to extend
        },
    },
  plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/aspect-ratio'),
      require('@tailwindcss/typography'),

      plugin(({ addVariant, e }) => {
          addVariant('label-checked', ({ modifySelectors, separator }) => {
              modifySelectors(
                  ({ className }) => {
                      const eClassName = e(`label-checked${separator}${className}`); // escape class
                      const yourSelector = 'input[type="radio"]'; // your input selector. Could be any
                      return `${yourSelector}:checked ~ .${eClassName}`; // ~ - CSS selector for siblings
                  }
              )
          })
      }),
  ],
}

