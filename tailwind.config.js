module.exports = {
  content: [
    './app/Views/**/*.php',
    './app/Views/**/*.html',
    './public/**/*.js',
    './public/**/*.html',
  ],
  theme: {
    extend: {
      padding: {
        '10%': '10%',
      },
      animation: {
        float: 'float 3s ease-in-out infinite',
        'fade-in': 'fade-in 2s ease-out forwards',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        'fade-in': {
          '0%': { opacity: 0 },
          '100%': { opacity: 1 },
        },
      },
    },
  },
  plugins: [],
};
